<?php 
echo $this->Form->create('Task');
echo $this->Form->hidden('id');
echo $this->Form->hidden('creator_id');
echo $this->Form->input('project_id');
echo $this->Form->input('title');
echo $this->Form->end('Update');