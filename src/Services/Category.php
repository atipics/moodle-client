<?php

namespace atipics\MoodleClient\Services;

use atipics\MoodleClient\Entities\Category as CategoryItem;
use atipics\MoodleClient\Entities\Dto\Category as CategoryDto;
use atipics\MoodleClient\Entities\CategoryCollection;

/**
 * Class Category
 * @package atipics\MoodleClient\Services
 */
class Category extends Service
{
    public function getAllWithCourses($flatList = false)
    {
        $courseService = new Course($this->client);
        $response = $this->sendRequest('core_course_get_categories');
        $categoriesIndexed = [];
        $maxDepth = 0;
        foreach ($response as $category) {
          $maxDepth = $maxDepth < $category['depth'] ? $category['depth'] : $maxDepth;
          $categoriesIndexed[$category['id']] = $category;
          $categoriesIndexed[$category['id']]['courses'] = $courseService->getByField('category', $category['id']);
          $categoriesIndexed[$category['id']]['categories'] = new CategoryCollection();
        }

        for ($i = $maxDepth; $i > 1; --$i) {
          foreach ($categoriesIndexed as $category) {
            if ($category['depth'] == $i) {
              foreach ($categoriesIndexed as $parentCategory) {
                if ( $parentCategory['id'] == $category['parent'] ) {
                  $parentCategory['categories']->add( new CategoryItem( $category ) );
                  unset($categoriesIndexed[$category['id']]);
                }
              }
            }
          }
        }
        $categoriesIndexed = $this->getCollection($categoriesIndexed);
        if ( $flatList ) {
          return $this->getFlatList( $categoriesIndexed );
        }
        return $categoriesIndexed;
    }

    protected function getFlatList($categories, $path = '')
    {
      $courses = [];
      foreach ($categories as $category) {
        foreach ($category->courses as $course) {
          $course->path = $path . $category->name . ' / ';
          $courses[] = $course;
        }
        $courses = array_merge( $courses, $this->getFlatList( $category->categories, $category->name . ' / ' ) );
      }
      return $courses;
    }

    protected function getCollection(array $categories)
    {
        $items = [];
        foreach ($categories as $category) {
            $items[] = new CategoryItem($category);
        }

        return new CategoryCollection(...$items);
    }
}
