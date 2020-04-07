<?php

namespace Atipics\MoodleClient\Entities\Dto;

use Atipics\MoodleClient\Entities\Entity;

/**
 * Class Category
 * @package Atipics\MoodleClient\Entities\Dto
 */
class Category extends Entity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * Description format (1 = HTML, 0 = MOODLE, 2 = PLAIN or 4 = MARKDOWN)
     * @var integer
     */
    public $descriptionFormat;

    /**
     * @var integer
     */
    public $parent;

    /**
     * @var integer
     */
    public $sortorder;

    /**
     * @var integer
     */
    public $coursecount;

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var string
     */
    public $path;
}
