<?php

namespace atipics\MoodleClient\Services;

use atipics\MoodleClient\Entities\Course as CourseItem;
use atipics\MoodleClient\Entities\Dto\Course as CourseDto;
use atipics\MoodleClient\Entities\CourseCollection;

/**
 * Class Course
 * @package atipics\MoodleClient\Services
 */
class Course extends Service
{
    /**
     * Get all courses by ids
     * @param array $ids
     * @return CourseCollection
     */
    public function getAll(array $ids = [])
    {
        $categories = $this->sendRequest('core_course_get_categories', ['options' => ['ids' => $ids]]);
        for ($i = 0; $i < count($categories); ++$i) {
          $courses = $this->getByField('category', $categories[$i]['id']);
          $categories[$i]['courses'] = $courses;

        }
        return $categories;
    }

    /**
     * Get course by field
     * @param $field
     * @param $value
     * @return CourseCollection
     */
    public function getByField($field, $value)
    {
        $arguments = [
            'field' => $field,
            'value' => $value,
        ];

        $response = $this->sendRequest('core_course_get_courses_by_field', $arguments);
        return $this->getCourseCollection($response['courses']);
    }

    /**
     * Create new course
     * @param \atipics\MoodleClient\Entities\Dto\Course[] ...$courses
     * @return CourseCollection
     */
    public function create(CourseDto ...$courses)
    {
        $response = $this->sendRequest(
            'core_course_create_courses',
            [
                'courses' => $this->prepareEntityForSending(...$courses)
            ]
        );

        return $this->getCourseCollection($response);
    }

    /**
     * Delete courses by ids
     * @param array $ids
     * @return mixed
     */
    public function delete(array $ids = [])
    {
        $response = $this->sendRequest('core_course_delete_courses', ['courseids' => $ids]);

        return $response;
    }

    /**
     * Get course collection by course array
     * @param array $courses
     * @return CourseCollection
     */
    protected function getCourseCollection(array $courses)
    {
        $courseItems = [];
        foreach ($courses as $courseItem) {
            $courseItems[] = new CourseItem($courseItem);
        }

        return new CourseCollection(...$courseItems);
    }
}
