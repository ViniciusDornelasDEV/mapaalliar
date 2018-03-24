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
       /*$serviceFuncionario = $this->getServiceLocator()->get('FuncionarioGestor');
        $funcionarios = $serviceFuncionario->getRecordsFromArray(array('lider' => 'S'));

        foreach ($funcionarios as $funcionario) {
            //selecionar usuários que são do gestor mais de unidade diferente
            
            $matricula = $this->retirarZero($funcionario['matricula']);
            echo 'UPDATE tb_funcionario SET matricula = "'.$matricula.'" WHERE id = '.$funcionario['id'].';<br>';
        }
        die('retirei 0 a esquerda!'); */

        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());
        $ausencias = false;
        $ferias = false;
        $acoes = false;
        $ajudas = false;
        $empresa = false;
        $unidade = false;
        $dataInicio = false;

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);

        $formRelatorio = new formRelatorio('frmRelatorio');
        $formRelatorio->setData(array('data_referencia' => date('d/m/Y')));

        $anotacoesAusencias = false;
        $anotacoesFerias = false;
        $anotacoesAcoes = false;
        $anotacoesAjudas = false;


        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();

            if(isset($dados['data_referencia'])){
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
                    
                    //pesquisar funcionários de férias
                    $ferias = $this->getServiceLocator()
                        ->get('Ferias')
                        ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim, 'unidade' => $unidade['id']));

                    //pesquisar ações disciplinares
                    $acoes = $this->getServiceLocator()
                        ->get('AcaoDisciplinar')
                        ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id']));

                    //pesquisar ajudas
                    $ajudas = $this->getServiceLocator()
                        ->get('Ajuda')
                        ->getAjudas(array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade' => $unidade['id']));
                }
                
            }

            $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
            $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 1)->toArray();
            $anotacoesFerias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 2)->toArray();
            $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 3)->toArray();
            $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 4)->toArray();


        }

        return new ViewModel(array(
                'ausencias'     =>  $ausencias,
                'ferias'        =>  $ferias,
                'acoes'         =>  $acoes,
                'ajudas'        =>  $ajudas,
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
        $formRelatorio->setData(array('data_referencia' => date('d/m/Y')));

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['data_referencia'])){
                $formRelatorio->setData($dados);
                if($formRelatorio->isValid()){
                    $this->relatorioDiario($formRelatorio->getData(), false, $usuario['funcionario']);
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
        
        //pesquisar funcionários de férias
        $ferias = $this->getServiceLocator()
            ->get('Ferias')
            ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ações disciplinares
        $acoes = $this->getServiceLocator()
            ->get('AcaoDisciplinar')
            ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ajudas
        $ajudas = $this->getServiceLocator()
            ->get('Ajuda')
            ->getAjudas(array('inicio' => $dataInicio, 'fim' => $dataFim), $usuario['funcionario']);


        $serviceAnotacoes = $this->getServiceLocator()->get('AnotacoesDashboard');
        $anotacoesAusencias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 1)->toArray();
        $anotacoesFerias = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 2)->toArray();
        $anotacoesAcoes = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 3)->toArray();
        $anotacoesAjudas = $serviceAnotacoes->getAnotacoes($dataInicio, $dataFim, 4)->toArray();

        return new ViewModel(array(
                'ausencias' =>  $ausencias,
                'ferias'    =>  $ferias,
                'acoes'     =>  $acoes ,
                'ajudas'    =>  $ajudas,
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
        $data = $data['data_referencia'];
        $formRelatorio = new formRelatorio('frmRelatorio');
        $dataBR = $formRelatorio->converterData($data);
        
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
        
         $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
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
        //pesquisar ausencias do mês
        if($unidade){
            $ausencias = $this->getServiceLocator()
                ->get('Ausencia')
                ->getAusencias(array('inicio' => $data, 'fim'    => $data, 'unidade' => $unidade));
        }else{
            $ausencias = $this->getServiceLocator()
                ->get('Ausencia')
                ->getAusencias(array('inicio' => $data, 'fim'    => $data), $gestor);
        }
        //percorrer ausencias
        foreach ($ausencias as $ausencia) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $dataBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ausência');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $ausencia->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $ausencia->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $ausencia->nome_funcionario);
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'E'.$linha)->applyFromArray(
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
                ->getFerias(array('inicio_inicio' => $data, 'inicio_fim'    => $data, 'unidade' => $unidade));
        }else{
            $ferias = $this->getServiceLocator()
                ->get('Ferias')
                ->getFerias(array('inicio_inicio' => $data, 'inicio_fim'    => $data), $gestor);
        }
        //percorrer férias
        foreach ($ferias as $feria) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $dataBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Férias');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $feria->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $feria->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $feria->nome_funcionario);
            $linha++;
        }

        if($unidade){
            $acoes = $this->getServiceLocator()
                ->get('AcaoDisciplinar')
                ->getAcoes(array('inicio' => $data, 'fim'    => $data, 'unidade' => $unidade));
        }else{
            $acoes = $this->getServiceLocator()
                ->get('AcaoDisciplinar')
                ->getAcoes(array('inicio' => $data, 'fim'    => $data), $gestor);
            
        }
        //percorrer acoes disciplinares
        foreach ($acoes as $acao) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $dataBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ação disciplinar');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $acao->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $acao->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $acao->nome_funcionario);

            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'E'.$linha)->applyFromArray(
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
                ->getAjudas(array('inicio' => $data, 'fim' => $data, 'unidade' => $unidade));
        }else{
            $ajudas = $this->getServiceLocator()
                ->get('Ajuda')
                ->getAjudas(array('inicio' => $data, 'fim' => $data), $gestor);
        }

        //percorrer ajudas
        foreach ($ajudas as $ajuda) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$linha, $dataBR);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$linha, 'Ajuda');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$linha, $ajuda->nome_empresa);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$linha, $ajuda->nome_unidade);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$linha, $ajuda->nome_funcionario);

            $objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':'.'E'.$linha)->applyFromArray(
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.'E'.$linha)->applyFromArray($style);

        //largura
        foreach(range('A','E') as $columnID) {
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
