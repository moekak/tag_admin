<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");



class TagsRangeModel{
    public $pdo;
    public $feedback;
    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }

    public function checkExistedData($admin_id, $code_range, $trigger_type, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    *
                FROM 
                    `tags_range` 
                WHERE 
                    code_range = :code_range 
                AND 
                    trigger_type = :trigger_type 
                AND 
                    admin_id = :admin_id
                AND
                    domain_id = :domain_id
                AND 
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':code_range', $code_range);
            $statement->bindValue(':trigger_type', $trigger_type);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagDataWithRange($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    *
                FROM 
                    `tags_range` 
                WHERE
                    admin_id = :admin_id
                AND
                    domain_id = :domain_id
                AND 
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function checkTagActive($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `tags_range` LEFT JOIN domains ON domains.original_parent_id = tags_range.domain_id WHERE tags_range.admin_id = :admin_id AND ((tags_range.domain_id = :domain_id) OR (tags_range.domain_id = domains.original_parent_id)) AND tags_range.is_active = '1'");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function checkTagActive2($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `tags_range` WHERE tags_range.admin_id = :admin_id AND tags_range.domain_id = :domain_id AND tags_range.is_active = '1'");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function checkValidTagIDWithRange($admin_id, $id){
        try{
            $statement = $this->pdo->prepare("SELECT id FROM `tags_range` WHERE admin_id = :admin_id AND id = :id AND is_active = '1'");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagsRangeData($admin_id, $id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                   *
                FROM `tags_range` 
                WHERE 
                    id = :id 
                AND 
                    admin_id = :admin_id
                AND
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getMatchedTagData($admin_id, $domain_id, $position, $type, $code, $excluded_code){
        // カラム名を安全な値のリストから確認
        $query = 
            "SELECT 
                $position 
            FROM 
                `tags_range` 
            WHERE 
                admin_id = :admin_id 
            AND 
                domain_id = :domain_id 
            AND 
                JSON_CONTAINS(code_range, JSON_QUOTE(:code_range))
            AND
                trigger_type = :trigger_type  
            AND NOT 
                JSON_CONTAINS(excluded_code, JSON_QUOTE(:code_range))
            AND 
                is_active = '1'";
        try{

            if(isset($excluded_code) && $excluded_code !== ""){
                $query .= " AND NOT JSON_CONTAINS(excluded_code, JSON_QUOTE(:excluded_code))";
            } 
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
            $statement->bindValue(':domain_id', $domain_id, PDO::PARAM_INT);
            $statement->bindValue(':trigger_type', $type);
            $statement->bindValue(':code_range', $code);
            if(isset($excluded_code) && $excluded_code !== ""){
                $statement->bindValue(':excluded_code', $excluded_code);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getAdCodeAndTagsWithRange($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    code_range,
                    tag_head,
                    tag_body,
                    trigger_type
                FROM `tags_range` 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id
                AND
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getAdCodeAndTagsWithRangeAndParentTag($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    code_range,
                    tag_head,
                    tag_body,
                    trigger_type
                FROM `tags_range` 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id
                AND
                    is_active = '1'
                AND
                    parent_tag_id != NULL
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getTagDataWithCodeRange($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    *
                FROM 
                    `tags_range` 
                WHERE 
                    admin_id = :admin_id
                AND
                    domain_id = :domain_id
                AND
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getExcludedCode($admin_id, $domain_id, $id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    excluded_code
                FROM 
                    `tags_range` 
                WHERE 
                    admin_id = :admin_id
                AND
                    domain_id = :domain_id
                AND
                    id = :id
                AND
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':id', $id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function insertTagInfo($domain_id, $admin_id, $tag_head, $tag_body, $code_range, $trigger, $parent_tag_id, $excluded_code){

        if(isset($excluded_code)){
            $excludedCode = $excluded_code;
        }else{
            $excludedCode = '[]';
        }
        try{
            $statement = $this->pdo->prepare("INSERT INTO `tags_range` (`domain_id`, `admin_id`, `tag_head`, `tag_body`, `code_range`, `trigger_type`, `parent_tag_id`, `excluded_code`) VALUES (:domain_id, :admin_id, :tag_head, :tag_body, :code_range, :trigger_type, :parent_tag_id, :excluded_code)");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->bindValue(':code_range', $code_range);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->bindValue(':parent_tag_id', $parent_tag_id);
            $statement->bindValue(':excluded_code', $excludedCode);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function insertExcludedCode($domain_id, $admin_id, $id, $code){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET 
                    excluded_code = :excluded_code 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    id = :id
             ");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':excluded_code', $code);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function DeleteExcludedCode($domain_id, $admin_id, $id){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET 
                    excluded_code = '[]'
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    id = :id
             ");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function updateTagHeadWithAdCode($admin_id, $domain_id, $code_range, $tag_head, $trigger){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET 
                    tag_head = JSON_MERGE( tag_head, :tag_head) 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    code_range = :code_range 
                AND 
                    trigger_type = :trigger_type
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':code_range', $code_range);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function updateTagBodyWithAdCode($admin_id, $domain_id, $code_range, $tag_body, $trigger){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET 
                    tag_body = JSON_MERGE( tag_body, :tag_body) 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    code_range = :code_range 
                AND 
                    trigger_type = :trigger_type
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':code_range', $code_range);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function deleteTagInfoWithRange($domain_id, $admin_id){
        try{
            $statement = $this->pdo->prepare("DELETE FROM `tags_range` WHERE domain_id = :domain_id AND admin_id = :admin_id AND parent_tag_id IS NOT NULL");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function deactivateTag($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("UPDATE tags_range SET is_active = '0' WHERE domain_id = :domain_id AND admin_id = :admin_id" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function deactivateIndividualTag($admin_id, $id){
        try{
            $statement = $this->pdo->prepare("UPDATE tags_range SET is_active = '0' WHERE id = :id AND admin_id = :admin_id" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagReferenceData($admin_id, $keyword){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domains.id AS domain_id,
                    tags_range.domain_id AS tag_domain_id,
                    domains.* 
                FROM 
                    `domains` 
                INNER JOIN `tags_range`ON `tags_range`.domain_id = `domains`.id 
                WHERE 
                    domains.admin_id = :admin_id 
                AND
                    domain_name LIKE :keyword 
                AND
                    domains.is_deleted = '0'
                AND
                    domains.tag_reference_id IS NULL
                AND 
                    tags_range.is_active = '1'
                
                GROUP BY domains.id
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':keyword', $keyword);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    // public function getTagReferenceDataForEdit($admin_id, $self_domain_id, $keyword){
    //     try{
    //         $statement = $this->pdo->prepare(
    //             "SELECT 
    //                 domains.id AS domain_id,
    //                 tags_range.domain_id AS tag_domain_id,
    //                 domains.* 
    //             FROM 
    //                 `domains` 
    //             INNER JOIN `tags_range`ON `tags_range`.domain_id = `domains`.id
    //             WHERE 
    //                 (domains.id != :exclude_id)
    //             AND 
    //                 domain_name LIKE :keyword 
    //             AND
    //                 domains.admin_id = :admin_id 
    //             AND
    //                 domains.is_deleted = '0'
    //             AND 
    //                 tags_range.is_active = '1'

    //         ");
    //         $statement->bindValue(':admin_id', $admin_id);
    //         $statement->bindValue(':exclude_id', $self_domain_id);
    //         $statement->bindValue(':keyword', $keyword);
        
    //         $statement->execute();
    //         return $statement->fetchAll(PDO::FETCH_ASSOC);

    //     }catch(PDOException $e){
    //         $this->feedback->logError($e->getMessage());
    //         SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
    //         exit;
    //     }
    // }

    public function updateTagData($admin_id, $domain_id, $id, $parent_tag_id, $code_range, $trigger_type){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET parent_tag_id = :parent_tag_id, 
                    code_range = :code_range, 
                    trigger_type = :trigger_type
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    id = :id
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':parent_tag_id', $parent_tag_id);
            $statement->bindValue(':code_range', $code_range);
            $statement->bindValue(':trigger_type', $trigger_type);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function updateTagHeadForEdit($admin_id, $domain_id, $id, $tag_head){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags_range
                SET 
                    tag_head = :tag_head 
                WHERE 
                    domain_id = :domain_id AND id = :id AND admin_id = :admin_id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function updateTagBodyForEdit($admin_id, $domain_id, $id, $tag_body){
        try{
            $statement = $this->pdo->prepare("UPDATE tags_range SET tag_body = :tag_body WHERE domain_id = :domain_id AND admin_id = :admin_id AND id = :id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function searchTagDataWithRange($keyword, $admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    `tags_range` 
                WHERE 
                    code_range LIKE :keyword 
                AND     
                    admin_id = :admin_id
                AND
                    domain_id = :domain_id
                AND is_active = '1' 
                ORDER BY created_at DESC
                ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':keyword', $keyword);
            $statement->bindValue(':domain_id', $domain_id);

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    
    public function deleteTagsRange($id){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                   tags_range
                SET 
                    is_active = '0'
                WHERE 
                    admin_id = :id
                " );
            $statement->bindValue(':id', $id);
            $statement->execute();
            
        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
}