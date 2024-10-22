<?php
require_once dirname(__FILE__) . "/../src/config/conf.php";


class TagDataProcess{
    public function generateFilePath($domain_id){
        return [
            "tag_directory" =>  TAG_FILE_PATH . "tags/" . $domain_id ,
            "tagRange_directory" => TAG_FILE_PATH . "tags_range/" . $domain_id ,
            "tagCode_directory" =>TAG_FILE_PATH . "tags_code/" . $domain_id 
        ];
    }

    // すべてのタグのファイルパスを探して配列で返す
    public function findFolderPath($dir){
        $filesArray = [];
        if(is_dir($dir)){
            $files = array_diff(scandir($dir), array('.','..'));
            foreach($files as $file){
                $filePath = "$dir/$file";
                if (is_dir($filePath)){
                    $filesArray = array_merge($filesArray, $this->findFolderPath($filePath));
                }else{
                    array_push($filesArray, $filePath);
                }
            }
        }
        return $filesArray;
    }


    // タグコードの配列を返す(範囲指定の場合)
    public function getTagCode($dir){
        $filePath_array = [];
        $filePath_array = $this->findFolderPath($dir);
        $tagCode_array = [];

        if(count($filePath_array) > 0){
            foreach($filePath_array as $path){
                $path_array = explode("/", $path);
                $tag_code = $path_array[7];
                array_push($tagCode_array, $tag_code);
            }
        }

        return $tagCode_array;
    }


    private function buildFilePath($directory, $code, $subDir, $position){
        return "$directory/$code/$subDir/$position/tag.txt";
    }

    private function getFileContent($filePath){
        if(file_exists($filePath) ){
            $content = file_get_contents($filePath);
            if($content !== false){
                return $content;
            }
        } 

        return "";
    }

    private function readTagFilesWithCode($tagCodes, $tag_code,$subDirs, $directory, $position){
        $data = "";
        foreach($tagCodes as $code){
            if($code === $tag_code){
                foreach($subDirs as $subDir){
                    $filePath = $this->buildFilePath($directory, $code, $subDir, $position);
                    $data.= $this->getFileContent($filePath);
                }
            }
        }
        return $data;
    }

    private function readTagFilesWithoutCode($subDirs, $directory, $position){
        $data = "";
        foreach($subDirs as $subDir){
            $filePath = $this->buildFilePath($directory, "all", $subDir, $position);
            $data .= $this->getFileContent($filePath);
        }

        return $data ;
    }

    // #########################################################
    //                  tagデータを取得
    // #########################################################
    public function getIndexTagHead($dir, $tag_code){

        $directory_tagRange = $dir["tagRange_directory"];
        $directory_tagCode  = $dir["tagCode_directory"];
        $directory_tag      = $dir["tag_directory"];

        $tagCodes_tagRange = $this->getTagCode($directory_tagRange);
        $tagCodes_tagCode  = $this->getTagCode($directory_tagCode);

        // $tagCodesのデータが重複してるデータが入っているため、重複をなくす
        $uniqueTagCodes = array_unique($tagCodes_tagRange);
        $uniqueTagCodes2 = array_unique($tagCodes_tagCode);

        $data    = "";
        $subDirs = ["index", "all_pages"];

        $data .= $this->readTagFilesWithCode($uniqueTagCodes, $tag_code, $subDirs, $directory_tagRange, "tag_head");
        $data .= $this->readTagFilesWithCode($uniqueTagCodes2, $tag_code, $subDirs, $directory_tagCode, "tag_head");
        $data .= $this->readTagFilesWithoutCode($subDirs,  $directory_tag, "tag_head");

        return $data;
    }

    public function getIndexTagBody($dir, $tag_code){

        $directory_tagRange = $dir["tagRange_directory"];
        $directory_tagCode  = $dir["tagCode_directory"];
        $directory_tag      = $dir["tag_directory"];

        $tagCodes_tagRange = $this->getTagCode($directory_tagRange);
        $tagCodes_tagCode  = $this->getTagCode($directory_tagCode);

        // $tagCodesのデータが重複してるデータが入っているため、重複をなくす
        $uniqueTagCodes = array_unique($tagCodes_tagRange);
        $uniqueTagCodes2 = array_unique($tagCodes_tagCode);

        $data    = "";
        $subDirs = ["index", "all_pages"];

        $data .= $this->readTagFilesWithCode($uniqueTagCodes, $tag_code, $subDirs, $directory_tagRange, "tag_body");
        $data .= $this->readTagFilesWithCode($uniqueTagCodes2, $tag_code, $subDirs, $directory_tagCode, "tag_body");
        $data .= $this->readTagFilesWithoutCode($subDirs,  $directory_tag, "tag_body");

        return $data;
    }

    public function getRdTagHead($dir, $tag_code){

        $directory_tagRange = $dir["tagRange_directory"];
        $directory_tagCode  = $dir["tagCode_directory"];
        $directory_tag      = $dir["tag_directory"];

        $tagCodes_tagRange = $this->getTagCode($directory_tagRange);
        $tagCodes_tagCode  = $this->getTagCode($directory_tagCode);

        // $tagCodesのデータが重複してるデータが入っているため、重複をなくす
        $uniqueTagCodes = array_unique($tagCodes_tagRange);
        $uniqueTagCodes2 = array_unique($tagCodes_tagCode);

        $data    = "";
        $subDirs = ["rd", "all_pages"];

        $data .= $this->readTagFilesWithCode($uniqueTagCodes, $tag_code, $subDirs, $directory_tagRange, "tag_head");
        $data .= $this->readTagFilesWithCode($uniqueTagCodes2, $tag_code, $subDirs, $directory_tagCode, "tag_head");
        $data .= $this->readTagFilesWithoutCode($subDirs,  $directory_tag, "tag_head");

        return $data; 
    }

    public function getRdTagBody($dir, $tag_code){

        $directory_tagRange = $dir["tagRange_directory"];
        $directory_tagCode  = $dir["tagCode_directory"];
        $directory_tag      = $dir["tag_directory"];

        $tagCodes_tagRange = $this->getTagCode($directory_tagRange);
        $tagCodes_tagCode  = $this->getTagCode($directory_tagCode);

        // $tagCodesのデータが重複してるデータが入っているため、重複をなくす
        $uniqueTagCodes = array_unique($tagCodes_tagRange);
        $uniqueTagCodes2 = array_unique($tagCodes_tagCode);

        $data    = "";
        $subDirs = ["rd", "all_pages"];

        $data .= $this->readTagFilesWithCode($uniqueTagCodes, $tag_code, $subDirs, $directory_tagRange, "tag_body");
        $data .= $this->readTagFilesWithCode($uniqueTagCodes2, $tag_code, $subDirs, $directory_tagCode, "tag_body");
        $data .= $this->readTagFilesWithoutCode($subDirs,  $directory_tag, "tag_body");

        return $data; 
    }
}