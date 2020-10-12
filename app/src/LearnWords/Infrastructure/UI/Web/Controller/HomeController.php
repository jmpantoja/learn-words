<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Infrastructure\UI\Web\Controller;

use LearnWords\Infrastructure\UI\GraphQl\Resolver\StatsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    /**
     * @var StatsQuery
     */
    private StatsQuery $query;

    public function __construct(StatsQuery $query)
    {
        $this->query = $query;
    }

    public function __invoke()
    {

$aa =        $this->query->byUser('28408286-0245-11eb-98fa-0242ac130007');

dump($aa);
        die();
        return $this->render('app/index.html.twig');
    }


//    private VocabularyRepository $repository;
//
//    private UserRepository $userRepository;

//    public function __construct(UserRepository $userRepository, VocabularyRepository $repository)
//    {
//        $this->userRepository = $userRepository;
//        $this->repository = $repository;
//
//    }
//
//    public function __invoke(): Response
//    {
//
//        $user = $this->userRepository->findOneBy([
//            'id' => '28408286-0245-11eb-98fa-0242ac130007'
//        ]);
//        $criteria = new ExamCriteria($user, ExamType::FAILED(), new Limit(10));
//
//        $list = $this->repository->getExamByCriteria($criteria);
//
//        /** @var Vocabulary $question */
//        foreach ($list as $question){
//            echo sprintf("%s<br/>", $question->getWord());
//        }
//
//        die;
//        return $this->render('app/index.html.twig');
//    }
}
