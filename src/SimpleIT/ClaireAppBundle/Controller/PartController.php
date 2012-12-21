<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Model\ApiRequest;

/**
 * Part controller
 *
 * The part controller is used to handle all the actions for
 * the parts of a course
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartController extends CourseBaseController
{
    /**
     * Show a part
     *
     * @param Request $request      The request
     * @param string  $categorySlug The slug of the category
     * @param string  $courseSlug   The slug of the course
     * @param string  $partSlug     The slug of the part
     *
     * @return Response
     */
    public function readAction(Request $request, $categorySlug, $courseSlug,
                    $partSlug)
    {
        //         $configuration = array('1' => array(null, 'title-1'),
        //                 '2' => array(null, 'title-2', 'title-3')
        //         );
        /* Get the Category */
        //FIXME ApiResult
        $categoryRequest = $this->getClaireApi('categories')
            ->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);
        $this->checkObjectFound($category);

        /* Get the Course */
        //FIXME ApiResult
        $courseRequest = $this->getClaireApi('courses')->getCourse($courseSlug);
        $course = $this->getClaireApi()->getResult($courseRequest);
        $this->checkObjectFound($course);

        /* Check if the course is in the category */
        $category = $category->getContent();
        $course = $course->getContent();
        if ($course['category']['id'] != $category['id']) {
            //FIXME Message
            throw $this
                ->createNotFoundException('The course is not in this category');
        }

        /* Get the Part */
        //FIXME ApiResult
        $partRequest = $this->getClaireApi('parts')
            ->getPart($courseSlug, $partSlug);
        $part = $this->getClaireApi()->getResult($partRequest);
        $this->checkObjectFound($part);
        $part = $part->getContent();

        /* Get the Part content (only for 1b or 2c) */
        //FIXME ApiResult
        //TODO Constantes
        if ($course['displayLevel'] == 1 || $part['type'] != 'title-1') {
            $options = new ApiRequestOptions();
            $options->setFormat(ApiRequest::FORMAT_HTML);
            $requests['content'] = $this->getClaireApi('parts')
                ->getPart($courseSlug, $partSlug, $options);
        }
        /*
         * Prepare request
         */
        /* Get the TOC */
        $requests['courseToc'] = $this->getClaireApi('courses')
            ->getCourseToc($courseSlug);
        /* Get the Introduction */
        $requests['partIntroduction'] = $this->getClaireApi('parts')
            ->getIntroduction($courseSlug, $partSlug);
        /* Get the tags */
        $requests['partTags'] = $this->getClaireApi('parts')
            ->getPartTags($courseSlug, $partSlug);
        $requests['courseTags'] = $this->getClaireApi('courses')
            ->getCourseTags($courseSlug);
        /* Get the metadatas */
        $requests['partMetadatas'] = $this->getClaireApi('parts')
            ->getPartMetadatas($courseSlug, $partSlug);
        $requests['courseMetadatas'] = $this->getClaireApi('courses')
            ->getCourseMetadatas($courseSlug);

        /* Get the responses */
        $results = $this->getClaireApi()->getResults($requests);

        /*
         * Retrieve
         */

        /* Retrieve the toc */
        $toc = $results['courseToc']->getContent();

        /* Retrieve the content */
        $content = null;
        if (isset($results['content'])) {
            $content = $results['content']->getContent();
        }
        $introduction = $results['partIntroduction']->getContent();

        /* Retrieve the tags */
        $partTags = $results['partTags']->getContent();
        $courseTags = $results['courseTags']->getContent();

        /* Retrieve the metadatas */
        $partMetadatas = $results['partMetadatas']->getContent();
        $courseMetadatas = $results['courseMetadatas']->getContent();

        /* Set the tags */
        $tags = $partTags;
        if (is_null($tags)) {
            $tags = $courseTags();
        }

        /* Set the icon */
        $icon = $this->getOneMetadata('image', $partMetadatas);
        if (is_null($icon)) {
            $icon = $this->getOneMetadata('image', $courseMetadatas);

        }
        /* Set the difficulty */
        $difficulty = $this->getOneMetadata('difficulty', $partMetadatas);
        if (is_null($difficulty)) {
            $difficulty = $this->getOneMetadata('difficulty', $courseMetadatas);

        }

        //TODO
        $date = new \DateTime();
        $part['updatedAt'] = $date->setTimestamp(strtotime($part['updatedAt']));

        //TODO
        // Alterate
        $pagination = $this->get('simpleit.claire.course')
            ->setPagination($part, $toc,
                ($course['displayLevel'] == 1) ? array('title-2', 'title-3')
                                : array('title-1'));

        // Breadcrumb
        $this->makeBreadcrumb($category, $course, $part, $toc);

        //        // Restrict TOC
        //        $toc = $this->get('simpleit.claire.course')->restrictTocForTitle(
        //                $part,
        //                $toc,
        //                (is_null($titleType) && $course['displayLevel'] == 1)  ? 'course' : $titleType);

        /* If part doesn't have any tags, use the course's tags */
        if (empty($tags)) {
            $tags = $results['courseTags']->getContent();
        }
        //FIXME
        if (!is_null($content)) {
            $content = preg_replace('/<h1(.*)h1>/', '', $content);
        }
        $displayLevel = $course['displayLevel'];

        return $this
            ->render($this->getView($part['displayLevel'], $part['type']),
                    array('title' => $part['title'],
                            'course' => $course,
                            'part' => $part,
                            'icon' => $icon,
                            'category' => $category,
                            'tags' => $tags,
                            'difficulty' => $difficulty,
                            'rate' => $this->getOneMetadata('aggregateRating', $partMetadatas),
                            'duration' => '',
                            'description' => $this->getOneMetadata('description ', $partMetadatas),
                            'updatedAt' => $part['updatedAt'],
                            'licence' => $this->getOneMetadata('license', $courseMetadatas),
                            'pagination' => $pagination,
                            'introduction' => $introduction,
                            'timeline' => $this->getTimeline($toc, $displayLevel),
                            'toc' => $this->getDisplayToc($toc, $displayLevel, $part['title']),
                            'contentHtml' => $content
                            ));
    }

    /**
     * Get the associated view for a specific context
     *
     * @param integer $displayLevel The level display for the course
     *                              Should be 1 or 2
     * @param string  $type         The type of the part
     *
     * @return string The associated view
     */
    private function getView($displayLevel, $type)
    {
        $this->checkPartDisplayLevelValidity($displayLevel);

        //FIXME constants
        if ($displayLevel == 1 && $type == 'title-1') {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2 && $type == 'title-2') {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2 && $type == 'title-3') {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        }
        return $view;
    }

//     /**
//      *
//      * @param type $toc
//      * @param type $displayLevel
//      * @param type $currentPartTitle
//      * @return type
//      */
//     private function prepareTimeline($toc, $displayLevel, $currentPartTitle)
//     {
//         $neededTypes = array();
//         if ($displayLevel == 0 || $displayLevel == 1) {
//             $neededTypes = array('title-1');
//         } else {
//             $neededTypes = array('title-1', 'title-2', 'title-3');
//         }
//         $timeline = array();
//         $i = 0;
//         $isOver = false;
//         if (!is_null($toc)) {
//             foreach ($toc as $part) {
//                 if ($part['type'] == 'title-1') {
//                     $part['isOver'] = $isOver;
//                     $timeline[$i] = $part;
//                     if ($part['title'] == $currentPartTitle) {
//                         $isOver = true;
//                     }
//                     $i++;
//                 }
//             }
//         }
//         return $timeline;
//     }

    /**
     * Make Breadcrumb
     *
     * @param array $baseCourse Base Course
     * @param array $category   Category
     * @param array $course     Course
     * @param array $toc        TOC
     */
    private function makeBreadcrumb($category, $course, $part, $toc)
    {
        $points = array('course' => 0, 'title-1' => 1, 'title-2' => 2,
        'title-3' => 3,
        );

        // BreadCrumb
        $breadcrumb = $this->get('apy_breadcrumb_trail');
        $breadcrumb
            ->add($category['title'], 'SimpleIT_Claire_categories_view',
                array('slug' => $category['slug']));

        $breadcrumb
            ->add($course['title'], 'course_view',
                array('categorySlug' => $category['slug'],
                'courseSlug' => $course['slug']
                ));

        if ($course['displayLevel'] == 2 && !empty($toc)) {
            //TODO
        }
        //        if (!empty($toc))
        //        {
        //            foreach($toc as $key => $element)
        //            {
        //                if ($element['slug'] == $course['slug'])
        //                {
        //                    $types = array('title-1', $element['type']);
        //                    for($i = $key - 1; $i >= 0; $i--)
        //                    {
        //                        if (!in_array($toc[$i]['type'], $types) && $points[$toc[$i]['type']] < $points[$element['type']])
        //                        {
        //                            $types[] = $toc[$i]['type'];
        //                            $breadcrumb->add($toc[$i]['title'],
        //                                    'course_view',
        //                                    array(
        //                                        'categorySlug' => $category['slug'],
        //                                        'rootSlug'     => $baseCourse['slug'],
        //                                        'titleSlug'    => $toc[$i]['slug']
        //                                        )
        //                                    );
        //                        }
        //                    }
        //                    break;
        //                }
        //            }
        //        }
        $breadcrumb->add($part['title']);
    }

    /**
     * List courses
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $parameters = $request->query->all();

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->bindFilter($parameters, array('sort'));

        $coursesRequest = $this->getClaireApi('courses')->getCourses($options);
        $courses = $this->getClaireApi()->getResult($coursesRequest);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array('courses' => $courses->getContent(),
        'appPager' => $courses->getPager()
        );
        return $this->generateView($this->view, $this->viewParameters);
    }

    /**
     * Generate the rendered response
     *
     * @param string $view           The view name
     * @param array  $viewParameters Associated view parameters
     *
     * @return Response A Response instance
     */
    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
