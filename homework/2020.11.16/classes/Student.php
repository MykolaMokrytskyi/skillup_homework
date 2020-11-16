<?php


class Student extends SkillUpMember
{
    private array $homework;

    /**
     * @param array $homework
     */
    public function setHomework(array $homework): void
    {
        $this->homework = $homework;
    }

    /**
     * Sets make equal to true for every homework
     */
    public function makeHomework(): void
    {
        foreach ($this->homework as $date => &$homeworkData) {
            foreach ($homeworkData as $description => &$statuses) {
                $statuses['made'] = true;
            }
        }
        unset($homeworkData, $statuses);
        echo "{$this->getName()} has made his homework!<br><br>";
    }

    /**
     * Sets checked equal to true for every homework
     * @param object $mentor
     */
    public function checkHomework(object $mentor): void
    {
        if ($mentor instanceof \Mentor) {
            foreach ($this->homework as $date => &$homeworkData) {
                foreach ($homeworkData as $description => &$statuses) {
                    $statuses['checked'] = true;
                }
            }
            unset($homeworkData, $statuses);
            echo "{$mentor->getName()} has checked {$this->getName()}'s homework!<br><br>";
        }
    }
}