<?php
echo $this->Form->create();
echo $this->Form->hidden('id');
echo $this->Form->hidden('user_id');
echo $this->FormLayout->inputDateTimePair('started', 'stopped', ['label' => 'Time']);
echo $this->Form->input('task_id');
echo $this->Form->input('title', ['label' => 'Description']);
echo $this->Form->end('Update');