<?php

namespace Presta\SitemapBundle\Sitemap;

use Presta\SitemapBundle\SitemapEvents;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;

class Generator
{
	/**
	 * Event dispatcher
	 * @var EventDispatcher
	 */
	protected $dispatcher;
	
	protected $container;
	
	protected $sections = array();
	
	protected $files = array();
	
	protected $builder;
	
	
	
	public function __construct(Builder $builder, $dispatcher)
	{
        $this->builder = $builder;
        $this->dispatcher = $dispatcher;
	}
	
	
	/**
	 * Generate all datas
	 */
	public function generate()
	{
        $this->populate();
        $this->buildOutputFiles();
	}
	
	
	
	protected function populate()
	{
        // TODO : check lifetime
        if(true)
        {
            $event = new SitemapPopulateEvent($this);
            $this->dispatcher->dispatch(SitemapEvents::onSitemapPopulate, $event);
        }
	}
	
	
	protected function buildOutputFiles()
	{
		
	}
	
	
	/**
	 * Return a list of generated files of the sitemap
	 * 
	 * @return array {name: generationDate}
	 */
	public function getGeneratedFileList()
	{
		$list = array();
		
		foreach($this->sections as $section)
		{
			$file = $this->builder->buildSectionFiles($section);
            $list[$section->getName()] = $section->getGenerationDate();
		}
		
		return $list;
	}
	
    
    /**
     * Get generated file by its section's name
     * 
     * @param str $name
     * @return Section - may be null 
     */
    public function getGeneratedFile($name)
    {
        foreach($this->sections as $section){
            if($section->getName() == $name)
            {
                return $section;
            }
        }
        
        return null;
    }
    
    
    /**
     *Get or generate section
     * 
     * @param str $name
     * @param int $lifetime
     * @return Section 
     */
	public function getSection($name, $lifetime)
	{
		if(!array_key_exists($name, $this->sections)) 
		{
			$this->sections[$name] = new Section($name, $lifetime);
		}
		
		return $this->sections[$name];
	}
    
	
}