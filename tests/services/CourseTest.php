<?php

namespace Atipics\MoodleClient\Tests\Services;

use Atipics\MoodleClient\Clients\Adapters\RestClient;
use Atipics\MoodleClient\Clients\ClientAdapterInterface;
use Atipics\MoodleClient\Services\Course;
use Atipics\MoodleClient\Tests\MoodleTestCase;
use Atipics\MoodleClient\Entities\CourseCollection;
use Atipics\MoodleClient\Entities\Dto\Course as CourseDto;
use Atipics\MoodleClient\Entities\Course as CourseEntity;

/**
 * Class CourseTest
 * @package Atipics\MoodleClient\Tests\Services
 */
class CourseTest extends MoodleTestCase
{
    /**
     * @var ClientAdapterInterface
     */
    protected $client;

    /**
     * @var Course
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->client = new RestClient($this->getConnection());
        $this->service = new Course($this->client);
    }

    public function testGetAll()
    {
        $allCourses = $this->service->getAll();

        $courseDto = new CourseDto();
        $properties = $courseDto->getProperties();

        /** @var CourseEntity $course */
        foreach ($allCourses->toArray() as $course) {
            foreach ($properties as $property => $value) {
                $courseData = $course->toArray();
                $this->assertArrayHasKey($property, $courseData);
            }
        }
    }

    /**
     * @return CourseCollection
     */
    public function testCreate()
    {
        $courseDto = $this->buildCourse();
        $createdCourses = $this->service->create($courseDto);

        /** @var CourseEntity $course */
        foreach ($createdCourses as $course) {
            $courseData = $course->toArray();
            $this->assertArrayHasKey('id', $courseData);
            $this->assertEquals($course->shortName, $courseDto->shortName);
        }

        return $createdCourses;
    }

    /**
     * @param CourseCollection $courses
     * @depends testCreate
     */
    public function testGetByField($courses)
    {
        $createdCourses = $courses->toArray();

        /** @var CourseEntity $course */
        $createdCourse = $createdCourses[0];

        $allCourses = $this->service->getByField('shortname', $createdCourse->shortName);

        foreach ($allCourses as $course) {
            $this->assertEquals($createdCourse->shortName, $course->shortName);
        }
    }

    /**
     * @param CourseCollection
     * @depends testCreate
     */
    public function testDelete($courses)
    {
        $ids = [];
        /** @var CourseEntity $course */
        foreach ($courses as $course) {
            $ids[] = $course->id;
        }

        $this->service->delete($ids);
        $courses = $this->service->getAll($ids);

        $this->assertEquals($courses->toArray(), []);
    }

    /**
     * @return CourseDto
     */
    protected function buildCourse()
    {
        $courseDto = new CourseDto();
        $courseDto->shortName = 'shortName_' . uniqid();
        $courseDto->fullName = 'fullName_' . uniqid();
        $courseDto->categoryId = 1;

        return $courseDto;
    }
}
