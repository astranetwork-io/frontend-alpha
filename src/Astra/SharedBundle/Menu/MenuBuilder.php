<?php
namespace Astra\SharedBundle\Menu;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Services\SharedVariableService;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;
    private $sharedVariableService;

    public function __construct(FactoryInterface $factory, SharedVariableService $sharedVariableService)
    {
        $this->factory = $factory;
        $this->sharedVariableService = $sharedVariableService;
    }

    public function createMainMenu()
    {
        /**
         * Поодерживается многоуровневое меню
         * Иконки задаются через setAttribute('icon', 'fa-angellist');
         * Пример:
         * $menu = $this->factory->createItem('root');
         * $menu->addChild('Home', array('route' => 'homepage'));
         * $menu['Home']->addChild('Edit profile', array('route' => 'homepage'));
         * $menu['Home']->setAttribute('icon', 'fa-angellist');
         * $menu['Home']['Edit profile']->addChild('Third Menu', array('route' => 'homepage'));
         * return $menu;
         */

        $menu = $this->factory->createItem('root');


        $menu->addChild('cabinet',['route' => 'homepage','label'=>'main_menu.cabinet.name']);
        $menu['cabinet']->setAttribute('icon', 'fa-user');
        $menu['cabinet']->addChild('main_menu.cabinet.profile', array('route' => 'astra_profile_my_view'));
        $menu['cabinet']->addChild('main_menu.cabinet.contacts', array('route' => 'astra_contacts_index'));
        $menu['cabinet']->addChild('main_menu.cabinet.tasks', array('route' => 'astra_myprofile_task_list_agile'));
        $menu['cabinet']->addChild('main_menu.cabinet.calendar', array('route' => 'astra_myprofile_task_list_calendar'));
        $menu['cabinet']->addChild('main_menu.cabinet.posts', array('route' => 'astra_contacts_messages_index'));
        $menu['cabinet']->addChild('main_menu.cabinet.files', array('route' => 'astra_myprofile_files'));
        $cabinetTask = $this->sharedVariableService->get(SharedVariableService::NAME_CURRENT_TASK);
        if($cabinetTask)
        {
            $menu['cabinet']->addChild('main_menu.cabinet.add', ['route' => 'astra_myprofile_task_add', 'routeParameters' => []]);
            $menu['cabinet']->addChild('main_menu.cabinet.taskView', ['route' => 'astra_myprofile_task_view', 'routeParameters' => ['task'=>$cabinetTask->getId()]]);
            $menu['cabinet']->addChild('main_menu.cabinet.taskMod', ['route' => 'astra_myprofile_task_edit', 'routeParameters' => ['task'=>$cabinetTask->getId()]]);
            $menu['cabinet']['main_menu.cabinet.add']->setDisplay(false);
            $menu['cabinet']['main_menu.cabinet.taskView']->setDisplay(false);
            $menu['cabinet']['main_menu.cabinet.taskMod']->setDisplay(false);
        }

        $cabinetMessage = $this->sharedVariableService->get(SharedVariableService::NAME_PRIVATE_MESSAGE);
        if($cabinetMessage)
        {
            $menu['cabinet']->addChild('main_menu.cabinet.chat', ['route' => 'astra_contact_view_chat', 'routeParameters' => ['chatId'=>$cabinetMessage->getId()]]);
            $menu['cabinet']['main_menu.cabinet.chat']->setDisplay(false);
        }


        $menu->addChild('projects',['route' => 'astra_shared_project_index','label'=>'main_menu.projects.name']);
        $menu['projects']->setAttribute('icon', 'fa-space-shuttle');
        $menu['projects']->addChild('main_menu.projects.create', array('route' => 'astra_shared_project_add'));
        $menu['projects']->addChild('main_menu.projects.list', array('route' => 'astra_shared_project_list'));
        $menu['projects']->addChild('main_menu.projects.mosaic', ['route' => 'astra_shared_project_mosaic']);

        $project = $this->sharedVariableService->get(SharedVariableService::NAME_CURRENT_PROJECT);
        $projectTask = $this->sharedVariableService->get(SharedVariableService::NAME_CURRENT_PROJECT_TASK);
        if($project)
        {
            $menu['projects']->addChild('main_menu.projects.files', ['route' => 'astra_shared_project_view_files', 'routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.users', ['route' => 'astra_shared_project_view_users', 'routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.tasks', ['route' => 'astra_shared_project_task_list_agile', 'routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.taskAdd', ['route' => 'astra_shared_project_task_add', 'routeParameters' => ['id'=>$project->getId()]]);
            if($projectTask)
            {
                $menu['projects']->addChild('main_menu.projects.taskView', ['route' => 'astra_shared_project_task_view', 'routeParameters' => ['id'=>$project->getId(),'task'=>$projectTask->getId()]]);
                $menu['projects']->addChild('main_menu.projects.taskMod', ['route' => 'astra_shared_project_task_edit', 'routeParameters' => ['id'=>$project->getId(),'taskId'=>$projectTask->getId()]]);
                $menu['projects']['main_menu.projects.taskView']->setDisplay(false);
                $menu['projects']['main_menu.projects.taskMod']->setDisplay(false);
            }

            $menu['projects']->addChild('main_menu.projects.calendar', ['route' => 'astra_shared_project_task_list_calendar','routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.messages', ['route' => 'astra_shared_project_chat','routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.view', ['route' => 'astra_shared_project_view','routeParameters' => ['id'=>$project->getId()]]);
            $menu['projects']->addChild('main_menu.projects.edit', ['route' => 'astra_shared_project_edit','routeParameters' => ['id'=>$project->getId()]]);
        }


        $menu->addChild('finance',['route' => 'homepage','label'=>'main_menu.finance.name']);
        $menu['finance']->setAttribute('icon', 'fa-area-chart');
        $menu['finance']->addChild('main_menu.finance.summary', array('route' => 'astra_shared_finance_statistics'));
        $menu['finance']->addChild('main_menu.finance.orders', array('route' => 'astra_shared_finance_my_wallets'));
        $menu['finance']->addChild('main_menu.finance.transfer', array('route' => 'astra_shared_finance_transaction'));
        $menu['finance']->addChild('main_menu.finance.exchange', array('route' => 'astra_shared_finance_exchange'));

        $menu->addChild('job',['route' => 'homepage','label'=>'main_menu.job.name']);
        $menu['job']->setAttribute('icon', 'fa-area-chart');
        $menu['job']->addChild('main_menu.job.myResume', array('route' => 'homepage'));
        $menu['job']->addChild('main_menu.job.myVacancy', array('route' => 'homepage'));
        $menu['job']->addChild('main_menu.job.allResume', array('route' => 'homepage'));
        $menu['job']->addChild('main_menu.job.allVacancy', array('route' => 'homepage'));


        $menu->addChild('config',['route' => 'astra_shared_config_roles_index','label'=>'main_menu.config.name']);
        $menu['config']->setAttribute('icon', 'fa-sliders');
        $menu['config']->addChild('main_menu.config.roles', array('route' => 'astra_shared_config_roles_index'));
        $menu['config']['main_menu.config.roles']->addChild('roles_add', array('route' => 'astra_shared_config_roles_add'));
        $menu['config']['main_menu.config.roles']['roles_add']->setDisplay(false);

        $userRole = $this->sharedVariableService->get(SharedVariableService::NAME_CURRENT_USER_ROLE);
        if($userRole)
        {
            $menu['config']['main_menu.config.roles']->addChild('roles_edit', ['route' => 'astra_shared_config_roles_edit','routeParameters' => ['id'=>$userRole->getId()]]);
            $menu['config']['main_menu.config.roles']['roles_edit']->setDisplay(false);
        }

        $menu['config']->addChild('main_menu.config.accessMatrix', array('route' => 'homepage'));
        return $menu;
    }

    public function createUserMenu()
    {
        /**
         * Поодерживается одноуровневое меню
         * Иконок нет
         * Есть разделители, задаютя через  $menu['Home3']->setAttribute('divider-bottom', true);
         * $menu['Home3']->setAttribute('divider-top', true);
         *
         * Пример
         * $menu = $this->factory->createItem('root');
         * $menu->addChild('Home', array('route' => 'homepage'));
         * $menu->addChild('Home2', array('route' => 'homepage'));
         * $menu->addChild('Home3', array('route' => 'homepage'));
         * $menu->addChild('Home4', array('route' => 'homepage'));
         * $menu['Home3']->setAttribute('divider-bottom', true);
         * $menu['Home3']->setAttribute('divider-top', true);
         * return $menu;
         */

        $menu = $this->factory->createItem('root');
        $menu->addChild('profile', array('label'=>'menu.myProfileEdit','route' => 'astra_myprofile_edit'));

        $menu->addChild('logout', array('label'=>'menu.logout','route' => 'fos_user_security_logout'));
        $menu['logout']->setAttribute('divider-top', true);

        return $menu;
    }

    public function createMyProfileMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('edit', array('label'=>'myProfile.edit','route' => 'astra_myprofile_edit'));
        $menu->addChild('about_me', array('label'=>'myProfile.aboutMe','route' => 'astra_myprofile_about_me'));
        return $menu;
    }

    public function createProjectMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        if(empty($options['project']))return $menu;
        /**
         * @var Project $project
         */
        $project = $options['project'];

        $menu->addChild('messages', ['label'=>'projects.menu.messages','route' => 'astra_shared_project_view_messages','routeParameters' => ['id'=>$project->getId()]]);
        $menu->addChild('tasks', ['label'=>'projects.menu.tasks','route' => 'astra_shared_project_view_tasks','routeParameters' => ['id'=>$project->getId()]]);
        $menu->addChild('users', ['label'=>'projects.menu.users','route' => 'astra_shared_project_view_users','routeParameters' => ['id'=>$project->getId()]]);

        return $menu;
    }

}