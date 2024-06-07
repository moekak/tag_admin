<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");



class TagsModel{
    public $pdo;
    public $feedback;
    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }

    public function getTagsInfo($admin_id, $id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                   *
                FROM `tags` 
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

    public function getMatchedTagData($admin_id, $domain_id, $position, $type, $code){
        // カラム名を安全な値のリストから確認
        $query = 
            "SELECT 
                $position 
            FROM 
                `tags` 
            WHERE 
                admin_id = :admin_id 
            AND 
                domain_id = :domain_id 
            AND
                trigger_type = :trigger_type  
            AND 
                is_active = '1'";
        
        if(isset($code) && $code){
            $query .= " AND ad_code = :ad_code";
        }
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
            $statement->bindValue(':domain_id', $domain_id, PDO::PARAM_INT);
            $statement->bindValue(':trigger_type', $type);
            if(isset($code) && $code){
                $statement->bindValue(':ad_code', $code);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getAdCodeAndTags($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    ad_code,
                    tag_head,
                    tag_body,
                    trigger_type
                FROM `tags` 
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
    public function getAdCodeAndTagsWithParentTag($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    ad_code,
                    tag_head,
                    tag_body,
                    trigger_type
                FROM `tags` 
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

    public function getTagsWhenTriggerAll ($admin_id, $domain_id, $trigger){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    tag_head,
                    tag_body
                FROM `tags` 
                WHERE domain_id = :domain_id AND admin_id = :admin_id AND trigger_type = :trigger_type AND is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagsWithAdCode ($admin_id, $domain_id, $code, $trigger){
        try{
            $statement = $this->pdo->prepare(
            "SELECT 
                    tag_head,
                    tag_body
                FROM `tags` 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    admin_id = :admin_id 
                AND 
                    ad_code = :ad_code 
                AND 
                    trigger_type = :trigger_type
                AND 
                    is_active = '1'
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':ad_code', $code);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function insertTagInfo($domain_id, $admin_id, $tag_head, $tag_body, $ad_code, $trigger, $parent_tag_id){
        try{
            $statement = $this->pdo->prepare("INSERT INTO `tags` (`domain_id`, `admin_id`, `tag_head`, `tag_body`, `ad_code`, `trigger_type`, `parent_tag_id`) VALUES (:domain_id, :admin_id, :tag_head, :tag_body, :ad_code, :trigger_type, :parent_tag_id)");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->bindValue(':ad_code', $ad_code);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->bindValue(':parent_tag_id', $parent_tag_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function updateTagHead($admin_id, $domain_id, $trigger, $tag_head){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET tag_head = JSON_MERGE( tag_head, :tag_head) WHERE domain_id = :domain_id AND admin_id = :admin_id AND trigger_type = :trigger_type"  );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function updateTagBody($admin_id, $domain_id, $trigger, $tag_body){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET tag_body = JSON_MERGE( tag_body, :tag_body) WHERE domain_id = :domain_id AND admin_id = :admin_id AND trigger_type = :trigger_type");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function updateTagHeadWithAdCode($admin_id, $domain_id, $code, $tag_head, $trigger){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET tag_head = JSON_MERGE( tag_head, :tag_head) WHERE domain_id = :domain_id AND admin_id = :admin_id AND ad_code = :ad_code AND trigger_type = :trigger_type");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':ad_code', $code);
            $statement->bindValue(':tag_head', $tag_head);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function updateTagBodyWithAdCode($admin_id, $domain_id, $code, $tag_body, $trigger){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET tag_body = JSON_MERGE( tag_body, :tag_body) WHERE domain_id = :domain_id AND admin_id = :admin_id AND ad_code = :ad_code AND trigger_type = :trigger_type");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':ad_code', $code);
            $statement->bindValue(':tag_body', $tag_body);
            $statement->bindValue(':trigger_type', $trigger);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function deleteTagInfo($domain_id, $admin_id){
        try{
            $statement = $this->pdo->prepare("DELETE FROM `tags` WHERE domain_id = :domain_id AND admin_id = :admin_id AND parent_tag_id IS NOT NULL");
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function checkTagID($admin_id, $tag_id){
        try{
            $statement = $this->pdo->prepare("SELECT id FROM `tags` WHERE admin_id = :admin_id AND domain_id = :parent_tag_id AND is_active = '1'");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':parent_tag_id', $tag_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function checkTagActive($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `tags` LEFT JOIN domains ON domains.original_parent_id = tags.domain_id WHERE tags.admin_id = :admin_id AND ((tags.domain_id = :domain_id) OR (tags.domain_id = domains.original_parent_id)) AND tags.is_active = '1'");
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
            $statement = $this->pdo->prepare("SELECT * FROM `tags` WHERE tags.admin_id = :admin_id AND tags.domain_id = :domain_id AND tags.is_active = '1'");
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
    public function checkValidTagID($admin_id, $id){
        try{
            $statement = $this->pdo->prepare("SELECT id FROM `tags` WHERE admin_id = :admin_id AND id = :id AND is_active = '1'");
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
    public function deactivateTag($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET is_active = '0' WHERE domain_id = :domain_id AND admin_id = :admin_id" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function deactivateTagForCode($admin_id, $id){
        try{
            $statement = $this->pdo->prepare("UPDATE tags SET is_active = '0' WHERE id = :id AND admin_id = :admin_id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getSpecificTagsData($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `tags` WHERE admin_id = :admin_id AND domain_id = :domain_id AND is_active = '1' ORDER BY created_at DESC");
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

    

    public function updateTagData($admin_id, $domain_id, $id, $parent_tag_id, $ad_code, $trigger_type){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    tags 
                SET parent_tag_id = :parent_tag_id, 
                    ad_code = :ad_code, 
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
            $statement->bindValue(':ad_code', $ad_code);
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
            $statement = $this->pdo->prepare("UPDATE tags SET tag_head = :tag_head WHERE domain_id = :domain_id AND id = :id AND admin_id = :admin_id");
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
            $statement = $this->pdo->prepare("UPDATE tags SET tag_body = :tag_body WHERE domain_id = :domain_id AND admin_id = :admin_id AND id = :id");
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

    public function searchTagData($keyword, $admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    `tags` 
                WHERE 
                    ad_code LIKE :keyword 
                AND     
                    admin_id = :admin_id
                AND 
                    domain_id = :domain_id
                AND is_active = '1' 
                ORDER BY created_at DESC
                ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            $statement->bindValue(':keyword', $keyword);

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function deleteTags($id){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                   tags
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