Feature: Provide insight into how Symfony 4 behaves on the unhappy path

  In order to eliminate bad Album data
  As a JSON API developer
  I need to ensure Album data meets expected criteria

  Background:
    Given there are Albums with the following details:
      | title                              | track_count | release_date              |
      | some fake album name               | 12          | 2020-01-08T00:00:00+00:00 |
      | another great album                | 9           | 2019-01-07T23:22:21+00:00 |
      | now that's what I call Album vol 2 | 23          | 2018-02-06T11:10:09+00:00 |

  @t @symfony_4_edge_case
  Scenario: Must have a non-blank title
    Given the request body is:
      """
      {
        "title": "",
        "track_count": 7,
        "release_date": "2030-12-05T01:02:03+00:00"
      }
      """
    When I request "/album" using HTTP POST
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "status": "error",
        "errors": {
            "children": {
                "title": {
                    "errors": [
                        "This value should not be blank."
                    ]
                },
                "release_date": [],
                "track_count": []
            }
        }
    }
    """

  @symfony_4_edge_case
  Scenario: Must have a track count of one or greater
    Given the request body is:
      """
      {
        "title": "My album title",
        "track_count": 0,
        "release_date": "2030-12-05T01:02:03+00:00"
      }
      """
    When I request "/album" using HTTP POST
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "status": "error",
        "errors": {
            "children": {
                "title": [],
                "release_date": [],
                "track_count": {
                    "errors": [
                        "This value should be greater than 0."
                    ]
                }
            }
        }
    }
    """

  @symfony_4_edge_case
  Scenario: Must have a track count of one or greater
    Given the request body is:
      """
      {
        "title": "My album title",
        "track_count": -5,
        "release_date": "2030-12-05T01:02:03+00:00"
      }
      """
    When I request "/album" using HTTP POST
    Then the response code is 400
    And the response body contains JSON:
    """
    {
        "status": "error",
        "errors": {
            "children": {
                "title": [],
                "release_date": [],
                "track_count": {
                    "errors": [
                        "This value should be greater than 0."
                    ]
                }
            }
        }
    }
    """
