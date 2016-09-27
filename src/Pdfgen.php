<?php
/**
 * @package PDF Generator SDK
 * @author Christopher Keith <Christopher_Keith@brown.edu>
 */

namespace BrownUniversity\Pdfgen;


/**
 * Class Pdfgen
 */
class Pdfgen {

    /**
     * @var PSR-3 Logger
     */
    private $logger;

    /**
     * Pdfgen constructor.
     *
     * @param $logger
     * @throws Exception
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }
    
}
