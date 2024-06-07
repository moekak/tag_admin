<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");



class DomainsModel{
    public $pdo;
    public $feedback;
    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }

    public function insertDomainInfo($domain_info){
        try{
            $statement = $this->pdo->prepare(
                "INSERT INTO 
                    `domains` (`domain_category_id`, `admin_id`, `random_domain_id`, `parent_domain_id`, `original_parent_id`, `domain_name`, `domain_type`, `tag_reference_id`, `tag_reference_randomID`) 
                VALUES 
                    (:domain_category_id, :admin_id, :random_domain_id, :parent_domain_id, :original_parent_id, :domain_name, :domain_type, :tag_reference_id, :tag_reference_randomID)
            ");
            $statement->bindValue(':domain_category_id', $domain_info["domain_category_id"]);
            $statement->bindValue(':admin_id', $domain_info["admin_id"]);
            $statement->bindValue(':random_domain_id', $domain_info["random_domain_id"]);
            $statement->bindValue(':parent_domain_id', $domain_info["parent_domain_id"]);
            $statement->bindValue(':original_parent_id', $domain_info["original_parent_id"]);
            $statement->bindValue(':domain_name', $domain_info["domain_name"]);
            $statement->bindValue(':domain_type', $domain_info["domain_type"]);
            $statement->bindValue(':tag_reference_id', $domain_info["tag_reference_id"]);
            $statement->bindValue(':tag_reference_randomID', $domain_info["tag_reference_randomID"]);
            $statement->execute();

            return $this->pdo->lastInsertId();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getRandomID(){
        try{
            $statement = $this->pdo->prepare("SELECT random_domain_id FROM `domains`");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function searchDomainName($keyword, $admin_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    `domains` 
                WHERE 
                    domain_name LIKE :keyword 
                AND     
                    admin_id = :admin_id
                AND is_deleted = '0' 
                ORDER BY created_at DESC
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
    public function searchData($keyword){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    *, `domains`.id AS domain_id
                FROM 
                    `domains` 
                INNER JOIN `admins`ON `admins`.id = `domains`.admin_id 
                WHERE 
                    (`domains`.domain_name LIKE :keyword OR `domains`.random_domain_id LIKE :keyword OR `domains`.tag_reference_randomID LIKE :keyword)
                AND 
                    `domains`.is_deleted = '0' 
                ");

            $statement->bindValue(':keyword', $keyword);

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getParentDomainData($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domain_name
                FROM 
                    `domains` 
                WHERE 
                   id = :domain_id
                AND     
                    admin_id = :admin_id
                AND is_deleted = '0' 
                
                ORDER BY created_at DESC
                ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);

            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    // public function getDirectoryDomainData($admin_id, $domain_id){
    //     try{
    //         $statement = $this->pdo->prepare(
    //             "SELECT 
    //                 *
    //             FROM 
    //                 `domains` 
    //             WHERE 
    //                parent_domain_id = :domain_id
    //             AND     
    //                 admin_id = :admin_id
    //             AND 
    //                 domain_type = 'directory'
    //             AND 
    //                 is_deleted = '0' 
                
    //             ORDER BY created_at DESC
    //             ");
    //         $statement->bindValue(':admin_id', $admin_id);
    //         $statement->bindValue(':domain_id', $domain_id);

    //         $statement->execute();
    //         return $statement->fetchAll(PDO::FETCH_ASSOC);

    //     }catch(PDOException $e){
    //         $this->feedback->logError($e->getMessage());
    //         SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
    //         exit;
    //     }
    // }
    public function getOriginalDomainData($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domain_name
                FROM 
                    `domains` 
                WHERE 
                   id = :domain_id
                AND     
                    admin_id = :admin_id
                AND is_deleted = '0' 
            
                ORDER BY created_at DESC
                ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);

            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function searchDomainData($keyword, $admin_id, $category_id){
        $query = "  SELECT 
                        * 
                    FROM 
                        `domains` 
                    WHERE 
                        domain_name LIKE :keyword 
                    AND     
                        admin_id = :admin_id
                    AND 
                        is_deleted = '0' 
                    ";
        if(isset($category_id) && $category_id){
            $query .= " AND domain_category_id = :domain_category_id";
        }
        $query .= " ORDER BY created_at DESC";
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':keyword', $keyword);
            if(isset($category_id) && $category_id){
                $statement->bindValue(':domain_category_id', $category_id);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

  

    public function searchDomainNameForEdit($keyword, $admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `domains` WHERE domain_name LIKE :keyword AND admin_id = :admin_id AND id != :exclude_id AND is_deleted = '0' AND domain_type != 'directory' ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':keyword', $keyword);
            $statement->bindValue(':exclude_id', $domain_id);

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

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
                    tags.domain_id AS tag_domain_id,
                    domains.* 
                FROM 
                    `domains` 
                INNER JOIN `tags`ON `tags`.domain_id = `domains`.id 
                WHERE 
                    domains.admin_id = :admin_id 
                AND
                    domain_name LIKE :keyword 
                AND
                    domains.is_deleted = '0'
                AND
                    domains.tag_reference_id IS NULL
                AND 
                    tags.is_active = '1'
                
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
    //                 tags.domain_id AS tag_domain_id,
    //                 domains.* 
    //             FROM 
    //                 `domains` 
    //             INNER JOIN `tags` ON `tags`.domain_id = `domains`.id
    //             WHERE 
    //                 (domains.id != :exclude_id)
    //             AND 
    //                 domain_name LIKE :keyword 
    //             AND
    //                 domains.admin_id = :admin_id 
    //             AND
    //                 domains.is_deleted = '0'
    //             AND 
    //                 tags.is_active = '1'
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
    public function getDomainName($domain_name, $admin_id, $domain_id){
        $query = "SELECT `domain_name` FROM domains WHERE admin_id = :admin_id AND domain_name = :domain_name AND domain_type != 'directory' AND is_deleted = '0'";
        if(isset($domain_id) && $domain_id){
            $query .= " AND id != :domain_id";
        }
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_name', $domain_name);
            if(isset($domain_id) && $domain_id){
                $statement->bindValue(":domain_id", $domain_id);
            }
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainNameForDirectory($admin_id, $domain_id){
        $query = "SELECT `domain_name` FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'";

        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainNameWithTagReference($tag_reference_id , $admin_id){
        $query = "SELECT `domain_name`, `id` FROM domains WHERE admin_id = :admin_id AND (tag_reference_id = :tag_reference_id OR id = :tag_reference_id)  AND is_deleted = '0'";
 
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_reference_id', $tag_reference_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainNameWithTagReferenceDirectory($tag_reference_id , $admin_id){
        $query = "SELECT `domain_name`, `id`, `tag_reference_id`, `parent_domain_id` FROM domains WHERE admin_id = :admin_id AND  (tag_reference_id = :tag_reference_id OR id = :tag_reference_id) AND domain_type = 'directory' AND is_deleted = '0'";
 
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_reference_id', $tag_reference_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainRandomId($domain_id, $admin_id){
        $query = "SELECT `random_domain_id`, `tag_reference_randomID` FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainRandomId2($domain_id, $admin_id){
        $query = "SELECT 
        `tag_reference_randomID`,
        CASE 
          WHEN `tag_reference_randomID` IS NULL THEN `random_domain_id`
          ELSE NULL 
        END AS `random_domain_id`
      FROM domains 
      WHERE admin_id = :admin_id 
        AND id = :id 
        AND is_deleted = '0'
      ";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
            return $result;
            // return null; // データが存在しない場合、nullを返す

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagReferenceId($domain_id, $admin_id){
        $query = "SELECT `tag_reference_randomID` FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagReferneceID($domain_id, $admin_id){
        $query = "SELECT `tag_reference_id` FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagReferenceId2($domain_id, $admin_id){
        $query = "SELECT `tag_reference_id` FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainID($random_domain_id, $admin_id){
        $query = "SELECT `id` FROM domains WHERE random_domain_id = :random_domain_id AND admin_id = :admin_id AND is_deleted = '0'";
  
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':random_domain_id', $random_domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDomainInfo($admin_id, $category_id){
        $query = "SELECT * FROM domains WHERE admin_id = :admin_id AND is_deleted = '0' AND parent_domain_id IS NULL" ;
        
        if(isset($category_id) && $category_id !== ""){
            $query .= " AND domain_category_id = :domain_category_id";
        }
        $query .= " ORDER BY `created_at` DESC LIMIT 20";
        
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            if(isset($category_id) && $category_id !== ""){
                $statement->bindValue(':domain_category_id', $category_id);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getDomainInfoByScroll($admin_id, $start, $end, $category){
        $query = "SELECT * FROM domains WHERE admin_id = :admin_id AND is_deleted = '0' AND parent_domain_id IS NULL";

        if (isset($category) && $category !== "") {
            $query .= " AND domain_category_id = :domain_category_id";
        }

        $query .= " ORDER BY `created_at` DESC LIMIT :limit OFFSET :offset";

        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
            $statement->bindValue(':limit', (int)$start, PDO::PARAM_INT);
            $statement->bindValue(':offset', (int)$end, PDO::PARAM_INT);
            if(isset($category) && $category !== ""){
                $statement->bindValue(":domain_category_id", $category);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getParentDomain($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT domain_name FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagReference($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT domain_name FROM domains WHERE admin_id = :admin_id AND id = :id AND is_deleted = '0'" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function deactivateDomain($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("UPDATE domains SET is_active = '0' WHERE id = :id AND admin_id = :admin_id" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function activateDomain($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("UPDATE domains SET is_active = '1' WHERE id = :id AND admin_id = :admin_id" );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getSpecificDomainInfo($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domains.id AS domain_id, 
                    MIN(tags.parent_tag_id) AS parent_tag_id,
                    domains.* 
                FROM 
                    domains 
                LEFT JOIN 
                    `tags` ON domain_id = `domains`.id 
                WHERE 
                    `domains`.admin_id = :admin_id  AND `domains`.id = :id AND is_deleted = '0' LIMIT 1" 
                );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getSpecificDomainData($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    *
                FROM 
                    domains 
                WHERE 
                    `domains`.admin_id = :admin_id  AND `domains`.id = :id AND is_deleted = '0'" 
                );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getSpecificOriginalDomainInfo($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    domains 
                WHERE 
                    `domains`.admin_id = :admin_id  AND `domains`.id = :id AND is_deleted = '0'" 
                );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getCopyDomainData($admin_id, $domain_id, $domain_type){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    domains 
                WHERE 
                    `domains`.admin_id = :admin_id   
                AND 
                    domain_type = :domain_type
                AND
                    parent_domain_id = :id 
                AND 
                    is_deleted = '0'" 
                );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->bindValue(':domain_type', $domain_type);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getAllCopyDomainData($admin_id, $domain_id, $domain_type){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    * 
                FROM 
                    domains 
                WHERE 
                    `domains`.admin_id = :admin_id   
                AND 
                    domain_type = :domain_type
                AND
                    original_parent_id = :id 
                AND 
                    is_deleted = '0'" 
                );
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->bindValue(':domain_type', $domain_type);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function updateDomainInfo($domain_info){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    domains 
                SET 
                    domain_name = :domain_name
                WHERE 
                    admin_id = :admin_id 
                AND 
                    id = :id" );
            $statement->bindValue(':domain_name', $domain_info["domain_name"]);
            $statement->bindValue(':admin_id', $domain_info["admin_id"]);
            $statement->bindValue(':id', $domain_info["domain_id"]);
            $statement->execute();
            

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function checkDomainID($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT id FROM `domains` WHERE admin_id = :admin_id AND id = :id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function deleteDomain($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("UPDATE domains SET is_deleted = '1' WHERE id = :id AND admin_id = :admin_id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $domain_id);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function sortDomainDataByCategory($admin_id, $domain_category_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `domains` WHERE admin_id = :admin_id AND domain_category_id = :domain_category_id AND parent_domain_id IS NULL AND is_deleted = '0' ORDER BY created_at DESC LIMIT 20");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_category_id', $domain_category_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function sortDomainCopyDataByCategory($admin_id, $domain_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `domains` WHERE admin_id = :admin_id AND (original_parent_id = :domain_id OR parent_domain_id = :domain_id)  AND original_parent_id IS NOT NULL AND is_deleted = '0' ORDER BY created_at DESC");
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
    public function getCopyDataByCategory($admin_id, $domain_id, $category_id){
        $query = "SELECT * FROM `domains` WHERE admin_id = :admin_id AND original_parent_id = :domain_id AND domain_type = 'copy' AND original_parent_id IS NOT NULL AND is_deleted = '0'";
        if(isset($category_id) && $category_id !== ""){
            $query .= " AND domain_category_id = :domain_category_id";
        }
        $query .= " ORDER BY created_at DESC ";
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            if(isset($category_id) && $category_id !== ""){
                $statement->bindValue(':domain_category_id', $category_id);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getDirectoryDataByCategory($admin_id, $domain_id, $category_id){
        $query = "SELECT * FROM `domains` WHERE admin_id = :admin_id AND parent_domain_id = :domain_id AND domain_type = 'directory' AND original_parent_id IS NOT NULL AND is_deleted = '0'";
        if(isset($category_id) && $category_id !== ""){
            $query .= " AND domain_category_id = :domain_category_id";
        }
        $query .= " ORDER BY created_at ASC ";
        try{
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_id', $domain_id);
            if(isset($category_id) && $category_id !== ""){
                $statement->bindValue(':domain_category_id', $category_id);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getTagDataAndDomain($admin_id, $tag_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domains.id AS domain_id,
                    tags.domain_id AS tag_domain_id,
                    domains.* ,
                    tags.*
                FROM 
                    `domains` 
                INNER JOIN `tags`ON `tags`.domain_id = `domains`.id 
                WHERE 
                    domains.admin_id = :admin_id 
                AND
                    domains.is_deleted = '0'
                AND 
                    tags.is_active = '1'
                AND
                    tags.id = :tag_id
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_id', $tag_id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getTagDataWithRangeAndDomain($admin_id, $tag_id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    domains.id AS domain_id,
                    tags_range.domain_id AS tag_domain_id,
                    domains.* ,
                    tags_range.*
                FROM 
                    `domains` 
                INNER JOIN `tags_range`ON `tags_range`.domain_id = `domains`.id 
                WHERE 
                    domains.admin_id = :admin_id 
                AND
                    domains.is_deleted = '0'
                AND 
                    tags_range.is_active = '1'
                AND
                    tags_range.id = :tag_id
            ");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':tag_id', $tag_id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


    public function deleteDomains($id){
        try{
            $statement = $this->pdo->prepare(
                "UPDATE 
                    domains 
                SET 
                    is_deleted = '1'
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