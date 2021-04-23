# Simple logger over transaction
Two separately connection into database, one for logger second for other operations
# Instalation:
1. Run `composer install` in project dir
2. Create own config file .env (DB connection config)
3. Create database, in root dir run command: `php bin/console doctrine:database:create`
4. Create scheme: `php bin/console doctrine:schema:update --force`

# Usage:
For test run command: `php bin/console data:data`

`/src/Service/Data/DataService.php`


    /**
     * @param string $dataString
     * @return bool
     */
    public function insert(string $dataString): bool
    {
        $return = false;
        $this->em->beginTransaction();
        try {
        	// insert log via separately connection over this transaction
            $this->logService->insert('LOG: '.$dataString, LogsRepository::TYPE_INFO);
            $data = (new Data())
                ->setData($dataString);
            $this->em->persist($data);
            $this->em->flush();
            // create exception to terminate transaction
            //throw new \Exception('test');
            $this->em->commit();
            $return = true;
        } catch (AppException $ex) {
            $this->em->rollback();
            $this->logger->critical(__METHOD__, ['ex' => $ex->getMessage()]);
        }

        return $return;
    }
