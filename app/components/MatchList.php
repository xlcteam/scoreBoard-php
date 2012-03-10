<?php


class MatchList extends NControl
{
        /** @var NTableSelection */
        public $model;

	public function __construct($model)
	{
		parent::__construct();
                $this->model = $model;

	}


	public function render($group)
	{
		$template = $this->template;
                $template->matches = $this->model->getMatches()->where('groupID', $group);
                $template->unplayed = $this->model->getMatches()->where(array(
                        'groupID' => $group,
                        'date' => NULL
                ));
                $template->group = $group;

		$template->setFile(dirname(__FILE__) . '/MatchList.latte');
		$template->render();
	}

}
