<?php

/**
 * Group presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class GroupPresenter extends SecuredPresenter
{
        private $groups;

        private $events;
        
        public function renderDefault()
        {
                
        }
        
        public function renderNew($id = 0)
        {
        }

        public function renderList($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $this->groups = $this->getService('model')->getGroups();

                $row = $this->groups->get($id);
                if(!$row) {
                        throw new NBadRequestException('Group not found');
                }
 
                $this->template->group = $row;
                $this->template->events = $this->getService('model')->getEvents();

        }

	protected function createComponentGroupForm($id = 0)
	{
                $this->events = $this->getService('model')->getEvents();

		$form = new NAppForm;
		$form->addText('name', 'Name of the group:')
			->setRequired('Please provide a name.');
                
                $events = $this->events->fetchPairs('id', 'name');

                $form->addSelect('eventID', 'Group in event', $events)
                        ->setDefaultValue((int) $this->getParam('id'));

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'groupFormSubmitted');
		return $form;
	}
        
        public function groupFormSubmitted($form)
        {
                $this->groups = $this->getService('model')->getGroups();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $action = $this->getParam('action');
                        $values = $form->getValues();

                        if($action == 'edit') {
                                $this->groups->find($row)->update($values);
                                $this->flashMessage("Group '{$values->name}' saved.");
                        } else {
                                $this->groups->find($row)->insert($values);
                                $this->flashMessage("Group '{$values->name}' created.");
                        }
                        $this->redirect('Dashboard:');
                }
                
        }

        public function renderEdit($id = 0)
        {
                $this->groups = $this->getService('model')->getGroups();
                
                $form = $this['groupForm'];
                $form['send']->caption = 'Save';
                if(!$form->isSubmitted()) {
                        $row = $this->groups->get($id);
                        if(!$row) {
                                throw new NBadRequestException('Group not found');
                        }
                        $form->setDefaults($row);
                }

        }

        public function createComponentTeamList()
        {
                return new TeamList($this->getService('model'));
        }

        public function createComponentMatchList()
        {
                return new MatchList($this->getService('model'));
        }

        public function handleExport($id = 0)
        {
                if($id === 0)
                        throw new NBadRequestException('No groupID provided');

                $groups = $this->getService('model')->getGroups();

                $row = $groups->get($id);
                if(!$row) {
                        throw new NBadRequestException('Group not found');
                }
 
                $group = $row;
                $events = $this->getService('model')->getEvents();
                $results = $this->getService('model')->getResults();

                $teams = $results->where('groupID', $group->id)
                        ->order('points DESC, goal_diff DESC');
                $names = $this->getService('model')->getTeams();

                $matches = $this->getService('model')->getMatches()
                        ->where(array(
                                'groupID'=> $group->id,
                                'state' => 'played'
                        ));


		define('FPDF_FONTPATH', LIBS_DIR.'/fpdf/font/');
                $pdf = new PDF();

                $pdf->title($events[$group->eventID]->name.': '. $group->name);
                $pdf->AliasNbPages();
                $pdf->AddPage();

                $pdf->SetLeftMargin(26);
                $pdf->SetFont('Times','B',15);
                $pdf->Cell(16, 6, "Pos.", 'B', 0, 'C');
                $pdf->Cell(46, 6, "", 'B', 0);
                $pdf->Cell(16, 6, "P", 'B', 0, 'C');
                $pdf->Cell(16, 6, "S", 'B', 0, 'C');
                $pdf->Cell(16, 6, "W", 'B', 0, 'C');
                $pdf->Cell(16, 6, "D", 'B','B' , 'C');
                $pdf->Cell(16, 6, "L", 'B', 0, 'C');
                $pdf->Cell(16, 6, "Pts.", 'B', 0, 'C');

                $pdf->Ln(6);

                $pdf->SetFont('Times','',15);

                $i = 0;
                foreach($teams as $team){
                        $i++;
                        $pdf->Cell(16,10, "$i.", 0, 0, 'C');
                        $pdf->Cell(46,10, $names[$team->id]->name, 0, 0);
                        $pdf->Cell(16,10, $team->matches_played, 0, 0, 'C');
                        $pdf->Cell(16,10, $team->goal_diff, 0, 0, 'C');
                        $pdf->Cell(16,10, $team->wins, 0, 0, 'C');
                        $pdf->Cell(16,10, $team->draws, 0, 0, 'C');
                        $pdf->Cell(16,10, $team->loses, 0, 0, 'C');
                        $pdf->Cell(16,10, $team->points, 0, 0, 'C');

                        $pdf->Ln(6);
                }

                $pdf->Ln(16);
                
                foreach($matches as $match) {
                        $pdf->SetFont('Courier','I',15);
                        $pdf->Cell(70, 10, $names[$match->team1ID]->name . ' ', 0, 0, 'R');
                        $pdf->SetFont('Courier','B',18);
                        $pdf->Cell(13, 10, $match->team1goals . ":" . $match->team2goals, 0, 0);
                        $pdf->SetFont('Courier','I',15);
                        $pdf->Cell(70, 10, ' '. $names[$match->team2ID]->name, 0, 0);
                        $pdf->Ln(6);
                } 


                $pdf->Output();


        }



}
