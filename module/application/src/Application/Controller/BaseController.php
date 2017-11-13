<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

abstract class BaseController extends AbstractActionController {
    protected $sessao = 'sessao';
    private $alfabeto = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H',
                    9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q',
                    18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

    protected function verificarPesquisa($form){
        $this->sessao = new Container();
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            
            if(isset($dados->limpar)){
                unset($this->sessao->parametros);
                $dados = false;
            }else{
                if(isset($dados->pesquisar)){
                    $this->sessao->parametros = $dados;
                }
            }
        }

        if(isset($this->sessao->parametros)) {
            $form->setData($this->sessao->parametros);
            if($form->isValid()){
                $this->sessao->parametros = $form->getData();
            }
        }

        return $form;
    }

    /**
     * Get user information from session.
     * 
     * @access 	protected
     * @param 	mixed $property (default: null)
     * @return 	void
     */
    protected function getIdentity($property = null) {
        $storage = $this->getServiceLocator()->get('session');

        if (!$storage) {
            return false;
        }

        $data = $storage->read();

        if ($property && isset($data[$property])) {
            return $data[$property];
        }

        return $data;
    }

    /**
     * Returns TRUE if session is still valid (i.e. it hasn't expired).
     * 
     * @access public
     * @return void
     */
    public function sessionIsValid() {
        return time() <= $this->getIdentity('expiry');
    }

    protected function saveToS3($bucket, $src, $filename, $type) {

        $aws = $this->getServiceLocator()->get('aws');

        $s3 = $aws->get('s3');

        $result = $s3->putObject(array(
            'Bucket' => $bucket,
            'SourceFile' => $src,
            'Key' => $filename,
            'ContentType' => $type,
        ));

        return $result;
    }

    protected function deleteFromS3($filename, $bucket) {

        $aws = $this->getServiceLocator()->get('aws');

        $s3 = $aws->get('s3');

        $result = $s3->deleteObject(array(
            'Bucket' => $bucket,
            'Key' => $filename
        ));

        return $result;
    }

    public function getExtensao($name){
        $extensao = explode('.', $name);
        return $extensao[1];
    }

    public function download(){
        $sessao = new Container();
        $fileName = $sessao->offsetGet('xlsx');

        
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

    public function gerarExcel($campos, $pesquisa, $nomeRelatorio){

        $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator('Time Sistemas');
        $objPHPExcel->getProperties()->setTitle('Relatório de '.$nomeRelatorio);
        $objPHPExcel->getProperties()->setDescription('Relatório gerado pelo sistema mapa Alliar.');

        $objPHPExcel->setActiveSheetIndex(0);
        
        //cabeçalho
        $contadorLetras = 1;
        foreach ($campos as $cabecalho => $coluna) {
            $objPHPExcel->getActiveSheet()->SetCellValue($this->alfabeto[$contadorLetras].'1', $cabecalho);
            $contadorLetras++;
        }

        //corpo
        $linha = 1;
        foreach ($pesquisa as $dados) {
            $linha++;
            $contadorLetras = 1;
            foreach ($campos as $campo) {
                 $objPHPExcel->getActiveSheet()->SetCellValue($this->alfabeto[$contadorLetras].$linha, $dados[$campo]);
                 $contadorLetras++;
            }
        }


        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        
        $diretorio = 'public/relatorios';
        if(!file_exists($diretorio)){
            mkdir($diretorio);
        }

        $fileName = $diretorio.'/'.$nomeRelatorio.'.xlsx';
        $objWriter->save($fileName);
        
        $sessao = new Container();
        $sessao->arquivo = $fileName;

        return $this->redirect()->toRoute('download');

    }

    public function uploadImagem($arquivos, $caminho, $dados){
        if(!file_exists($caminho)){
            mkdir($caminho);
        }
        
        foreach ($arquivos as $nomeArquivo => $arquivo) {
            if(!empty($arquivo['tmp_name'])){
                $extensao = $this->getExtensao($arquivo['name']);
                $nomeArquivoServer = 'arquivo'.date('dhsi').$nomeArquivo;
                if(move_uploaded_file($arquivo['tmp_name'], $caminho.'/'.$nomeArquivoServer.'.'.$extensao)){
                    $dados[$nomeArquivo] = $caminho.'/'.$nomeArquivoServer.'.'.$extensao;
                }
            }else{
                unset($dados[$nomeArquivo]);
            }
        }

        return $dados;
    }
    
}
