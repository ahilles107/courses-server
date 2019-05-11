Feature:
  In order to work with course modules
  As a user
  I want to use course modules API

  Scenario: I want to list all course modules
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" module "Test Module" with description "Test module description"
    Given Course "Test course" module "Test Module 2" with description "Second module module description" and position 0
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and coverImage "lesson_cover.png" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given Lesson "Other test lesson" in "Test Module" with description "Other test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bd" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "[1].title" should be equal to "Test Module"
    And the JSON node "[1].description" should be equal to "Test module description"
    And the JSON node "[1].course.description" should exist
    And the JSON node "[1].position" should be equal to "1"
    And the JSON node "[1].lessons" should have 2 elements
    And the JSON node "[1].lessons[0].title" should be equal to "Test lesson"
    And the JSON node "[1].lessons[0].embed_code" should not exist
    And the JSON node "[1].lessons[0].completed" should be null
    And the JSON node "[1].lessons[0].href.coverImageUrl" should be equal to the string "http://localhost/assets/images/course/test-lesson.png"
    And the JSON node "[0].title" should be equal to "Test Module 2"
    And the JSON node "[0].description" should be equal to "Second module module description"
    And the JSON node "[0].position" should be equal to "0"
