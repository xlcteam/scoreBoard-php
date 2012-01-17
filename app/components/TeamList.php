<?php


class TeamList extends NControl
{
        /** @var NTableSelection */
        public $teams;

        /** @var NTableSelection */
        public $model;

	public function __construct($model)
	{
		parent::__construct();
                $this->model = $model;
                $this->teams = $model->getTeams();
	}


	public function render($id)
	{
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/TeamList.latte');
                $template->teams = $this->teams->where('groupID', $id);
		$template->render();
	}

        public function createComponentGroupList()
        {
                return new GroupList($this->model);
        }
}
