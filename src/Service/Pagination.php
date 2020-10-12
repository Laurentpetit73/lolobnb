<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination{

    private $entityClass;
    private $limit = 10;
    private $start;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager , Environment $twig, RequestStack $request , $templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
        
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    public function setCurrentPage($page){
        $this->currentPage = $page;
        return $this;
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getlimit(){
        return $this->limit;
    }

    public function setStart(){
        $this->start = $this->getCurrentPage()*$this->limit-$this->limit;
        return $this;
    }

    public function getStart(){
        return $this->start;
    }

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }

    public function getRoute(){
        return $this->route;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("vous n'avez pas specifié l'entité sur laquelle nous devons paginer ! 
            Utiliser la méthode setEntityClass() de votre obj Pagination");
        }
        $start = $this->currentPage*$this->limit-$this->limit;
        $repo = $this->manager->getRepository($this->entityClass);
        return $repo->findBy([],[],$this->limit,$start);

    }
    public function getPages(){
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        return ceil($total/$this->limit);

    }

    public function render(){
        $this->twig->display($this->templatePath,[
            'page'=> $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route]);

    }



}