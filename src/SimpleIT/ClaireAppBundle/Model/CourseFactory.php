<?php
namespace SimpleIT\ClaireAppBundle\Model;

use SimpleIT\ClaireAppBundle\Model\Course\Course;
use SimpleIT\AppBundle\Model\Paginator;

/**
 * Class CourseFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseFactory
{
    /**
     * Create a course
     *
     * @param array $courseResource
     *
     * @return Course
     */
    public static function create(array $courseResource)
    {
        $course = new Course();
        if (isset($courseResource['id'])) {
            $course->setId($courseResource['id']);
        }
        if (isset($courseResource['title'])) {
            $course->setTitle($courseResource['title']);
        }
        if (isset($courseResource['slug'])) {
            $course->setSlug($courseResource['slug']);
        }
        if (isset($courseResource['status'])) {
            $course->setStatus($courseResource['status']);
        }
        if (isset($courseResource['displayLevel'])) {
            $course->setDisplayLevel($courseResource['displayLevel']);
        }
        if (isset($courseResource['createdAt'])) {
            $course->setCreatedAt(new \DateTime($courseResource['createdAt']));
        }
        if (isset($courseResource['updatedAt'])) {
            $course->setUpdatedAt(new \DateTime($courseResource['updatedAt']));
        }
        if (isset($courseResource['category'])) {
            $category = CategoryFactory::create($courseResource['category']);
            $course->setCategory($category);
        }
        if (isset($courseResource['metadatas'])) {
            $course->setMetadatas($courseResource['metadatas']);
        }
        if (isset($courseResource['tags'])) {
            $course->setTags($courseResource['tags']);
        }
        if (isset($courseResource['authors'])) {
            $course->setAuthors($courseResource['authors']);
        }
        return $course;
    }

    /**
     * Create a collection of courses
     *
     * @param mixed $courseResources The resources [Array | Paginator]
     *
     * @return array The courses
     */
    public static function createCollection($courseResources)
    {
        $courses = array();

        foreach ($courseResources as $courseResource) {
            $course = self::create($courseResource);
            $courses[] = $course;
        }
        if($courseResources instanceof Paginator)
        {
            $courseResources->setData($courses);
            $courses = $courseResources;
        }

        return $courses;
    }
}