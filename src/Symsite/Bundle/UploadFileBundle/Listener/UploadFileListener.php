<?php

namespace Symsite\Bundle\UploadFileBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
Use Symsite\Bundle\UploadFileBundle\Entity\UploadFile;

class UploadFileListener
{
    protected $root_path;

    public function __construct($root_path)
    {
        $this->root_path = $root_path;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->injectRootPath($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->injectRootPath($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->injectRootPath($args);
    }

    protected function injectRootPath(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof UploadFile) {
            $entity->setRootPath($this->root_path);
        }
    }
}