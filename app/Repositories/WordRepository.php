<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/08/16
 * Time: 9:53 PM
 */

namespace App\Repositories;
use Storage;

class WordRepository
{
    public function readWord($path){
//        dd($path);
        $array = explode('.', $path);
        $extension = $array[count($array)-1];
        $real_path = storage_path('app/'.$path);
        if($extension == 'doc'){
                return $this->read_doc_file($real_path);
        }elseif($extension == 'docx'){
           $real_path = storage_path('app/'.$path);
        $zip  = new \ZipArchive;
        $zip->open($real_path);
        if(($index=$zip->locateName("word/document.xml")) !== false){
            $text = $zip->getFromIndex($index);
            $xml = simplexml_load_string($text,null, 0, 'w', true);
            $body = $xml->body;
            $word_string = '';
            foreach($body[0] as $key => $value) {
                $word_string.="<p>";
                if ($key == "p") {
                    foreach ($value->r as $kkey => $vvalue) {
                       $word_string.=(string)$vvalue->t;
                    }
                }
                $word_string.= "</p>";
            }
            $zip->close();
            return $word_string;

        }else{
            return false;
        }  
    }else{
        return false;
    }       

    }

    function read_doc_file($filename) {
     if(file_exists($filename))
    {
        if(($fh = fopen($filename, 'r')) !== false ) 
        {
           // $headers = fread($fh, 0xA00);

           // // 1 = (ord(n)*1) ; Document has from 0 to 255 characters
           // $n1 = ( ord($headers[0x21C]) - 1 );

           // // 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
           // $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );

           // // 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
           // $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );

           // // 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
           // $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );

           // // Total length of text in the document
           // $textLength = ($n1 + $n2 + $n3 + $n4);

           $extracted_plaintext = fread($fh, filesize($filename));

           // simple print character stream without new lines
           //echo $extracted_plaintext;

           // if you want to see your paragraphs in a new line, do this
           return nl2br($extracted_plaintext);
           // need more spacing after each paragraph use another nl2br
        }
    }   
    }

    function readFile($path){

    }

}