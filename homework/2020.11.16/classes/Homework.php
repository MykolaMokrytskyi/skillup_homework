<?php


class Homework
{
    private array $homework;

    /**
     * @param string $homeworkDate
     * @param string $homeworkDescription
     * @param object $author
     */
    public function addHomework(string $homeworkDate,
                                string $homeworkDescription,
                                object $author): void
    {
        if ($author instanceof \Mentor) {
            $this->homework[$homeworkDate] = [
                $homeworkDescription => [
                    'made' => false,
                    'checked' => false,
                ]
            ];
            echo "{$author->getName()} has added new homework 
                    with date equal to {$homeworkDate}: {$homeworkDescription}.<br><br>";
        }
    }

    /**
     * @return array
     */
    public function getHomework(): array
    {
        return $this->homework;
    }
}