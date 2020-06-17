<?php

namespace seventynine\Wordpress\Boilerplate\Blocks;

use Timber;

class Registerer
{
    const DEFAULT_PATH = STYLESHEETPATH . '/templates/modules/';

    const DEFAULT_SETTINGS = [
        'title'             => 'seventynine Block',
        'name'              => 'seventynine-block',
        'description'		=> 'seventynine Block',
        'category'			=> 'common',
        'icon'				=> 'admin-generic',
        'keywords'			=> ['seventynine'],
        'post_types'        => ['post', 'page'],
        'supports'          => [
            'align'             => ['full'],
            'multiple'          => true,
            'mode'              => true
        ]
    ];

    public function __construct(string $name, array $args = [], string $path = null)
    {
        $this->name = $name;
        $this->args = array_merge(self::DEFAULT_SETTINGS, $args);
        $this->blockArgs = $this->formatBlockSettings($this->name, $this->args);
        $this->path = isset($path) ? $path : self::DEFAULT_PATH;
        $this->registerBlock($this->blockArgs);
    }


    /**
     * Register Block
     * Registers ACF block with WordPress
     * @param array $settings
     */
    private function registerBlock(array $settings)
    {
        add_action('acf/init', function () use ($settings) {
            if (function_exists('acf_register_block')) {
                acf_register_block($settings);
            }
        });
    }


    /**
     * Format Block Settings
     * Adds name, title and callback to block settings.
     * @param array $settings
     * @return array
     */
    private function formatBlockSettings(string $name, array $settings)
    {
        $additionalArgs = [
            'title' => __(ucfirst($name)),
            'name' =>  $this->formatBlockName($name),
            'render_callback' => function ($block) {
                $this->renderBlock($block);
            }
        ];

        return array_merge($settings, $additionalArgs);
    }


    /**
     * Render Block
     * Renders an ACF block through Timber Twig
     * for use with WordPress and theme.
     * @param array $block
     * @return void
     */
    public function renderBlock(array $block)
    {
        $slug = $this->blockArgs['name'];

        if (file_exists($this->path . $slug . '.twig')) {
            $content = $this->getBlockContent($block);
            Timber::render($this->path . $slug . '.twig', $content);
        }
    }


    /**
     * Get Block Content
     * Gets all block field data
     * @param array $block
     * @return mixed
     */
    public function getBlockContent(array $block)
    {
        if (array_key_exists('id', $block)) {
            return get_fields($block['id']);
        }

        return null;
    }


    /**
     * Format Block Name
     * Ensures block names are consistent.
     * Removes the ACF prefix and replaces underscores and spaces with hyphens.
     * @param array $block
     * @return mixed
     */
    private function formatBlockName(string $name)
    {
        if (isset($name) && strlen($name) > 0) {
            $blockName = str_replace('acf/', '', $name);
            $formattedBlockName = preg_replace('/[\s\_]/', '-', $blockName);
            return strtolower($formattedBlockName);
        }

        return false;
    }
}
