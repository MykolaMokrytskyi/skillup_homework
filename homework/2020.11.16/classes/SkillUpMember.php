<?php


class SkillUpMember
{
    private string $name;
    private string $language;
    private const SCHOOL = 'SkillUp';

    public function __construct(string $name, string $language, bool $mentor = false)
    {
        $this->name = $name;
        $this->language = $language;

        $school = self::SCHOOL;
        $member = $mentor ? 'mentor' : 'student';

        echo "{$school} {$this->language}-{$member} {$this->name} successfully created.<br><br>";

    }

    /**
     * Returns instance name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns instance language
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}