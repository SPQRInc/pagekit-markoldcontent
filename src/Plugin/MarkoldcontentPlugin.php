<?php

namespace Spqr\Markoldcontent\Plugin;

use Pagekit\Application as App;
use Pagekit\Blog\Model\Post;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;


class MarkoldcontentPlugin implements EventSubscriberInterface
{
    
    /**
     * @var
     */
    protected $config;
    
    /**
     * @var
     */
    protected $route;
    
    /**
     * @var
     */
    protected $id;
    
    /**
     * MarkoldcontentPlugin constructor.
     */
    public function __construct()
    {
        $this->config = App::module('spqr/markoldcontent')->config();
    }
    
    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        
        $this->route = App::request()->attributes->get('_route');
        $this->id    = App::request()->attributes->get('id');
        
        if ($event['widget']) {
            return;
        }
        
        if ($this->config['autoinsert']) {
            $content = $event->getContent();
            
            if ($content && $this->check()) {
                if ($this->config['position'] == 'top') {
                    $content = $this->config['message'].$content;
                } else {
                    $content = $content.$this->config['message'];
                }
                $event->setContent($content);
            }
        }
    }
    
    /**
     * @param null $period
     *
     * @return bool
     */
    private function check($period = null)
    {
        if (!$period) {
            $period = $this->config['period'];
        }
        
        if (strpos($this->route, '@blog/id') !== false) {
            if (!App::module('blog')) {
                return false;
            }
            if (!$post = Post::where(['id = ?', 'status = ?', 'date < ?'],
                [$this->id, Post::STATUS_PUBLISHED, new \DateTime])->first()
            ) {
                return false;
            } else {
                if ($post->modified->diff(new \DateTime)->format('%a')
                    >= $period
                ) {
                    return true;
                }
            }
        } else {
            return false;
        }
        
        return false;
    }
    
    /**
     * @param \Pagekit\Content\Event\ContentEvent $event
     */
    public function onApplyPlugins(ContentEvent $event)
    {
        $event->addPlugin('markoldcontent', [
            $this,
            'applyPlugin',
        ]);
    }
    
    /**
     * @param array $options
     *
     * @return string
     */
    public function applyPlugin(array $options)
    {
        if (isset($options['period'])) {
            $period = $options['period'];
        } else {
            $period = $this->config['period'];
        }
        
        if ($this->check($period)) {
            return $this->config['message'];
        }
        
        return '';
    }
    
    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => [
                ['onApplyPlugins', 25],
                ['onContentPlugins', 25],
            ],
        ];
    }
}