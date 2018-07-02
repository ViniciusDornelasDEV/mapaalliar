<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

use Cliente\Form\cliente as formCliente;
use Application\Form\Contato as formContato;
use Application\Form\PesquisaDash as formPesquisa;
use Application\Form\PesquisaDashGestor as formPesquisaGestor;
use Application\Form\RelatorioDiario as formRelatorio;

class IndexController extends BaseController
{
    private function retirarZero($matricula){
        if(strcasecmp($matricula[0], "0") == 0){
            $matricula = substr($matricula, 1);
            return $this->retirarZero($matricula);
        }
        return $matricula;
    }

    public function indexAction()
    {   

        /*//IMPORTAR AREAS SETOREES E FUNCOES
        $inputFileType = \PHPExcel_IOFactory::identify('public/setores.xlsx');
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load('public/setores.xlsx');
        $objExcel = $objPHPExcel->getSheet(0); 
        $highestRow = $objExcel->getHighestRow(); 

        $serviceSetor = $this->getServicelocator()->get('Setor');
        $serviceFuncao = $this->getServicelocator()->get('Funcao');
        for ($i=2; $i <= $highestRow; $i++) { 
            $rowData = $objExcel->rangeToArray('A'.$i.':'.'D'.$i,
                                                NULL,
                                                true,
                                                true,
                                                false);
           

            $setores = $serviceSetor->getRecords($rowData[0][1], 'nome');
            if(!$setores){
                die('NAO ACHEI O SETOR '.$i);
            }

            foreach ($setores as $setor) {
                $funcao = $serviceFuncao->getRecordFromArray(array('setor' => $setor['id'], 'nome' => $rowData[0][2]));
                if($funcao){
                    echo 'UPDATE tb_funcao SET nome="'.$rowData[0][3].'" WHERE id = '.$funcao['id'].';<br>';
                }
            }

        }

        die();*/
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());
        $ausencias = false;
        $ausenciasAtestado = false;
        $ferias = false;
        $acoes = false;
        $ajudasRecebidas = false;
        $ajudasSolicitadas = false;
        $empresa = false;
        $unidade = false;
        $dataInicio = false;
        $dataFim = false;

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);

        $formRelatorio = new formRelatorio('frmRelatorio');
        $formRelatorio->setData(array('inicio_referencia' => date('d/m/Y'), 'fim_referencia' => date('d/m/Y')));

        $anotacoesAusencias = false;
        $anotacoesFerias = false;
        $anotacoesAcoes = false;
        $anotacoesAjudas = false;


        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();

            if(isset($dados['inicio_referencia'])){
                $formRelatorio->setData($dados);
                if($formRelatorio->isValid()){
                    $this->relatorioDiario($formRelatorio->getData(), $dados['unidade']);
                }
            }else{
                $formPesquisa->setData($dados);
                $empresa = $this->getServiceLocator()->get('Empresa')->getRecord($dados['empresa']);
                $unidade = $this->getServiceLocator()->get('Unidade')->getRecord($dados['unidade']);

                if($formPesquisa->isValid()){
                    $dados = $formPesquisa->getData();
                    //pegar período
                    if(empty($dados['inicio']) && empty($dados['fim'])){
                        $dataInicio = date('Y-m').'-01';
                        $dataFim = date("t", mktime(0,0,0,date('m'),'01',date('Y')));
                        $dataFim = date('Y-m').'-'.$dataFim;
                        
                    }else{
                        $dataInicio = $dados['inicio'];
                        $dataFim = $dados['fim'];
                    }
                    
                    //pesquisar ausencias do mês
                    $ausencias = $this->getServiceLocator()
                        ->get('Ausencia')
                        ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id']));
                    
                    $ausenciasAtestado = $this->getServiceLocator()
                        ->get('Ausencia')
                        ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id'], 'atestado' => 'S'));

                    //pesquisar funcionários de férias
                    $ferias = $this->getServiceLocator()
                        ->get('Ferias')
                        ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim, 'unidade' => $unidade['id']));

                    //pesquisar ações disciplinares
                    $acoes = $this->getServiceLocator()
                        ->get('AcaoDisciplinar')
                        ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id']));

                    //pesquisar ajudas
                    $serviceAjuda = $this->getServiceLocator()->get('Ajuda');
                    $ajudasRecebidas = $serviceAjuda->getAjudas(
                        array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade_destino' => $unidade['id']));

                    $ajudasSolicitadas = $serviceAjuda->getAjudas(
                        array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade' => $unidade['id'])
                    );

                }
                
                $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
                $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 1, $unidade['id'])->toArray();
                $anotacoesFerias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 2, $unidade['id'])->toArray();
                $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 3, $unidade['id'])->toArray();
                $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 4, $unidade['id'])->toArray();
            }
        }

        return new ViewModel(array(
                'ausencias'     =>  $ausencias,
                'ausenciasAtestado' => $ausenciasAtestado,
                'ferias'        =>  $ferias,
                'acoes'         =>  $acoes,
                'ajudasRecebidas'        =>  $ajudasRecebidas,
                'ajudasSolicitadas'      => $ajudasSolicitadas,
                'formPesquisa'  =>  $formPesquisa,
                'empresa'       =>  $empresa,
                'unidade'       =>  $unidade,
                'dataInicio'    =>  $dataInicio,
                'formRelatorio' =>  $formRelatorio,
                'anotacoesAusencias'    =>  $anotacoesAusencias,
                'anotacoesFerias'       =>  $anotacoesFerias,
                'anotacoesAcoes'        =>  $anotacoesAcoes,
                'anotacoesAjudas'       =>  $anotacoesAjudas
            ));
    }

    public function indexgestorAction(){
        $this->layout('layout/gestor');
        $formPesquisa = new formPesquisaGestor('formPesquisa');
        //pegar período
        $dataInicio = date('Y-m').'-01';
        $dataFim = date("t", mktime(0,0,0,date('m'),'01',date('Y')));
        $dataFim = date('Y-m').'-'.$dataFim;
        
        //pegar usuario logado
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        //form de relatorio diario
        $formRelatorio = new formRelatorio('frmRelatorio');
        $formRelatorio->setData(array('inicio_referencia' => date('d/m/Y'), 'fim_referencia' => date('d/m/Y')));

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['inicio_referencia'])){
                $formRelatorio->setData($dados);
                if($formRelatorio->isValid()){
                    $this->relatorioDiario($formRelatorio->getData(), false, $funcionario);
                }
            }else{
                $formPesquisa->setData($dados);
                if($formPesquisa->isValid()){
                    $dados = $formPesquisa->getData();
                    $dataInicio = $dados['inicio'];
                    $dataFim = $dados['fim'];
                }
            }
        }

        //pesquisar ausencias do mês
        $ausencias = $this->getServiceLocator()
            ->get('Ausencia')
            ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim), $usuario['funcionario']);
        
        $ausenciasAtestado = $this->getServiceLocator()
            ->get('Ausencia')
            ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'atestado' => 'S'), $usuario['funcionario']);
        

        //pesquisar funcionários de férias
        $ferias = $this->getServiceLocator()
            ->get('Ferias')
            ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ações disciplinares
        $acoes = $this->getServiceLocator()
            ->get('AcaoDisciplinar')
            ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ajudas
        $serviceAjuda = $this->getServiceLocator()->get('Ajuda');
        $ajudasRecebidas = $serviceAjuda->getAjudas(
            array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade_destino' => $funcionario['unidade']));

        $ajudasSolicitadas = $serviceAjuda->getAjudas(
            array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade' => $funcionario['unidade'])
        );

        $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
        $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 1, $funcionario['unidade'])->toArray();
        $anotacoesFerias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 2, $funcionario['unidade'])->toArray();
        $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 3, $funcionario['unidade'])->toArray();
        $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 4, $funcionario['unidade'])->toArray();

        return new ViewModel(array(
                'ausencias' =>  $ausencias,
                'ausenciasAtestado' => $ausenciasAtestado,
                'ferias'    =>  $ferias,
                'acoes'     =>  $acoes ,
                'ajudasRecebidas'    =>  $ajudasRecebidas,
                'ajudasSolicitadas'     => $ajudasSolicitadas,
                'inicio'    =>  $dataInicio,
                'fim'       =>  $dataFim,
                'formPesquisa'  =>  $formPesquisa,
                'funcionario'   =>  $funcionario,
                'formRelatorio' =>  $formRelatorio,
                'anotacoesAusencias'    =>  $anotacoesAusencias,
                'anotacoesFerias'       =>  $anotacoesFerias,
                'anotacoesAcoes'        =>  $anotacoesAcoes,
                'anotacoesAjudas'       =>  $anotacoesAjudas
            ));
    }

    private function relatorioDiario($data, $unidade = false, $gestor = false){
        $data_inicio = $data['inicio_referencia'];
        $data_fim = $data_inicio;
        if(!empty($data['fim_referencia'])){
            $data_fim = $data['fim_referencia'];
        }
        
        $formRelatorio = new formRelatorio('frmRelatorio');
        $inicioBR = $formRelatorio->converterData($data_inicio);
        $fimBR = $formRelatorio->converterData($data_fim);
        $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator('Time Sistemas');
        $objPHPExcel->getProperties()->setTitle('Relatório diário');
        $objPHPExcel->getProperties()->setDescription('Relatório gerado pelo sistema mapa Alliar.');

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Data');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Ação');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Empresa');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Unidade');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Funcionário');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Anotação');
        
         $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '305496'),
                ),
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'ffffff'),
                )
            )
        );

        $linha = 2;
        $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
        

        //pesquisar ausencias do mês
        if($unidade){
            $ausencias = $this->getServiceLocator()
                ->get('Ausencia')
                ->getAusencias(array('inicio' => $data_inicio, 'fim'    => $data_fim, 'unidade' => $unidade));
            $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 1, $unidade);
        }else{
            $ausencias = $this->getServiceLocator()
                ->get('Ausencia')
                ->getAusencias(array('inicio' => $data_inicio, 'fim'    => $data_fim), $gestor['id']);
            $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 1, $gestor['unidade']);
        }
        //percorrer ausencias
        foreach ($ausencias as $ausencia) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $inicioBR.' a '.$fimBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ausência');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $ausencia->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $ausencia->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $ausencia->nome_funcionario);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, '-');

            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'ebccd1'),
                    ),
                )
            );
            
            $linha++;
        }

        foreach ($anotacoesAusencias as $anotacao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha,  $formRelatorio->converterData($anotacao->data));
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Anotação de ausência');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $anotacao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $anotacao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, '-');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, $anotacao->texto);
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'ebccd1'),
                    ),
                )
            );
            
            $linha++;
        }

        if($unidade){
            $ferias = $this->getServiceLocator()
                ->get('Ferias')
                ->getFerias(array('inicio_inicio' => $data_inicio, 'inicio_fim'    => $data_fim, 'unidade' => $unidade));
            $anotacoesFerias = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 2, $unidade);
        }else{
            $ferias = $this->getServiceLocator()
                ->get('Ferias')
                ->getFerias(array('inicio_inicio' => $data_inicio, 'inicio_fim'    => $data_fim), $gestor['id']);
                $anotacoesFerias = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 2, $gestor['unidade']);
        }
        //percorrer férias
        foreach ($ferias as $feria) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $inicioBR.' a '.$fimBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Férias');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $feria->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $feria->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $feria->nome_funcionario);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, '-');
            $linha++;
        }

        foreach ($anotacoesFerias as $anotacao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha,  $formRelatorio->converterData($anotacao->data));
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Anotação de férias');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $anotacao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $anotacao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, '-');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, $anotacao->texto);
        
            $linha++;
        }


        if($unidade){
            $acoes = $this->getServiceLocator()
                ->get('AcaoDisciplinar')
                ->getAcoes(array('inicio' => $data_inicio, 'fim'    => $data_fim, 'unidade' => $unidade));
            $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 3, $unidade);
        }else{
            $acoes = $this->getServiceLocator()
                ->get('AcaoDisciplinar')
                ->getAcoes(array('inicio' => $data_inicio, 'fim'    => $data_fim), $gestor['id']);
            $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 3, $gestor['unidade']);
            
        }
        //percorrer acoes disciplinares
        foreach ($acoes as $acao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $inicioBR.' a '.$fimBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ação disciplinar');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $acao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $acao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $acao->nome_funcionario);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, '-');

            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'faebcc'),
                    ),
                )
            );

            $linha++;
        }

        foreach ($anotacoesAcoes as $anotacao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha,  $formRelatorio->converterData($anotacao->data));
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Anotação de ação disciplinar');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $anotacao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $anotacao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, '-');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, $anotacao->texto);
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'faebcc'),
                    ),
                )
            );
            
            $linha++;
        }



        if($unidade){
            $ajudas = $this->getServiceLocator()
                ->get('Ajuda')
                ->getAjudas(array('inicio' => $data_inicio, 'fim' => $data_fim, 'unidade' => $unidade));
             $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 4, $unidade);
        }else{
            $ajudas = $this->getServiceLocator()
                ->get('Ajuda')
                ->getAjudas(array('inicio' => $data_inicio, 'fim' => $data_fim), $gestor['id']);
             $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($data_inicio, $data_fim, 4, $gestor['unidade']);
        }

        //percorrer ajudas
        foreach ($ajudas as $ajuda) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $inicioBR.' a '.$fimBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ajuda');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $ajuda->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $ajuda->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $ajuda->nome_funcionario);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, '-');

            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'd6e9c6'),
                    ),
                )
            );

            $linha++;
        }

        foreach ($anotacoesAjudas as $anotacao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha,  $formRelatorio->converterData($anotacao->data));
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Anotação de ajuda');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $anotacao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $anotacao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, '-');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$linha, $anotacao->texto);
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'F'.$linha)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'd6e9c6'),
                    ),
                )
            );
            
            $linha++;
        }


        //centralizar        
        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.'F'.$linha)->applyFromArray($style);

        //largura
        foreach(range('A','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        
        $diretorio = 'public/relatorios';
        if(!file_exists($diretorio)){
            mkdir($diretorio);
        }

        $fileName = $diretorio.'/diario.xlsx';
        $objWriter->save($fileName);
        
        $sessao = new Container();
        $sessao->arquivo = $fileName;

        return $this->redirect()->toRoute('downloadByContainer');

    }

    public function comentariocalendarioAction(){
        $params = $this->getRequest()->getPost();
        $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
        //salvar o texto
        $texto = '';
        if(isset($params->texto)){
            //pesquisar texto
            $anotacao = $serviceAnotacoes->getRecordFromArray(array(
                            'tipo' => $params->tipo, 
                            'data' => $params->data, 
                            'unidade' => $params->unidade
                        ));
            if($anotacao){
                //alterar
                $serviceAnotacoes->update(array('texto' => $params->texto), array('id' => $anotacao->id));
            }else{
                //salvar
                $dadosInsert = array('data' => $params->data, 'tipo' => $params->tipo, 'texto' => $params->texto, 'unidade' => $params->unidade);
                $serviceAnotacoes->insert($dadosInsert);
            }
        }else{
             $texto = $serviceAnotacoes->getRecordFromArray(array('tipo' => $params->tipo, 'data' => $params->data, 'unidade' => $params->unidade));
             if($texto){
                $texto = $texto->texto;
             }
        }

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('texto' => $texto));
        return $view;
    }

    public function relatoriodiarioAction(){

    }

    public function downloadAction(){
        $service = $this->params()->fromRoute('service');

        $data = $this->getServiceLocator()->get($service)->getRecord($this->params()->fromRoute('id'));
        $fileName = $data[$this->params()->fromRoute('campo')];
        if(!is_file($fileName)) {
            //Não foi possivel encontrar o arquivo
            return false;
        }
        $fileContents = file_get_contents($fileName);

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Content-Length', strlen($fileContents));
        return $this->response;
    }

    public function downloadbycontainerAction(){
        $sessao = new Container();
        $fileName = $sessao->offsetGet('arquivo');
        
        if(!is_file($fileName)) {
            //Não foi possivel encontrar o arquivo
            return false;
        }
        $fileContents = file_get_contents($fileName);

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Content-Length', strlen($fileContents));
        return $this->response;
    }



}
