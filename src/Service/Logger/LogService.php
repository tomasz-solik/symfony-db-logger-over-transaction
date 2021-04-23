<?php
/**
 * logger - LogService.php
 *
 * Initial version by: Toamsz Solik
 * Initial version created on: 22.04.21 / 15:36
 */

namespace App\Service\Logger;


use App\Entity\Logger\Logs;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LogService
{
    /**
     * @var ContainerInterface
     */
    public $container;

    /**
     * @var EntityManagerInterface
     */
    private $loggerEm;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->container = $container;
        $this->loggerEm = $this->container->get('doctrine')->getManager('logger');
        $this->logger = $logger;
    }

    /**
     * @param string $log
     * @param string $type
     * @return bool
     */
    public function insert(string $log, string $type): bool
    {
        $return = false;
        try {
            $logs = (new Logs())
                ->setType($type)
                ->setLog($log);
            $this->loggerEm->persist($logs);
            $this->loggerEm->flush();
            $return = true;
        } catch (AppException $ex) {
            $this->logger->critical(__METHOD__, ['ex' => $ex->getMessage()]);
        }

        return $return;
    }

}