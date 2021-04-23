<?php
/**
 * logger - DataService.php
 *
 * Initial version by: Toamsz Solik
 * Initial version created on: 22.04.21 / 15:07
 */

namespace App\Service\Data;


use App\Entity\Data\Data;
use App\Exception\AppException;
use App\Repository\Logger\LogsRepository;
use App\Service\Logger\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DataService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var LogService
     */
    private $logService;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        LogService $logService
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->logService = $logService;
    }

    /**
     * @param string $dataString
     * @return bool
     */
    public function insert(string $dataString): bool
    {
        $return = false;
        $this->em->beginTransaction();
        try {
            //// insert log via separately connection over this transaction
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
}