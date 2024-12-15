Feature: Room Reservation
    In order to stay at the hotel
    As a guest
    I need to be able to make room reservations

    Background:
        Given the following rooms exist:
            | id | number | type     | capacity | base_price | status    |
            | 1  | 101    | standard | 2        | 100.00     | available |
            | 2  | 201    | suite    | 4        | 200.00     | available |
            | 3  | 301    | deluxe   | 3        | 150.00     | available |
        And the following guests exist:
            | id | name          | email              | phone       | loyalty_level |
            | 1  | John Smith    | john@example.com   | 1234567890  | gold          |
            | 2  | Jane Doe      | jane@example.com   | 0987654321  | silver        |
            | 3  | Bob Wilson    | bob@example.com    | 5555555555  | bronze        |
        And the following reservations exist:
            | id | room_id | check_in    | check_out   | status    | guest_id | total_price | primary_guest_id |
            | 1  | 2       | 2025-06-01  | 2025-06-05  | confirmed | 2        | 200.00      | 2                |

    Scenario: Successfully make a room reservation
        Given I am authenticated as "John Smith"
        When I try to reserve room "101" for the following stay:
            | check_in    | 2025-07-01 |
            | check_out   | 2025-07-05 |
        And I add "Bob Wilson" as an additional guest
        Then the reservation should be confirmed
        And the total price should be "360.00"
        And the following operation should be logged:
            | model         | Reservations         |
            | operation     | reservation_created  |
            | data          | room_number=101, guest_name=John Smith, check_in=2025-07-01, check_out=2025-07-05, total_price=360, additional_guests=1 |

    Scenario: Cannot reserve an already booked room
        Given I am authenticated as "John Smith"
        When I try to reserve room "201" for the following stay:
            | check_in    | 2025-06-03 |
            | check_out   | 2025-06-07 |
        Then I should see an error "Room is not available for selected dates"

    Scenario: Cannot exceed room capacity
        Given I am authenticated as "John Smith"
        When I try to reserve room "101" for the following stay:
            | check_in    | 2025-08-01 |
            | check_out   | 2025-08-05 |
        And I add "Jane Doe" as an additional guest
        And I add "Bob Wilson" as an additional guest
        Then I should see an error "Total number of guests (3) exceeds room capacity (2)"

    Scenario: Apply loyalty discounts correctly
        Given I am authenticated as "Jane Doe"
        When I try to reserve room "301" for the following stay:
            | check_in    | 2025-09-01 |
            | check_out   | 2025-09-04 |
        Then the reservation should be confirmed
        And the total price should be "427.5"
        And the following operation should be logged:
            | model         | Reservations         |
            | operation     | reservation_created  |
            | data          | room_number=301, guest_name=Jane Doe, check_in=2025-09-01, check_out=2025-09-04, total_price=427.5, additional_guests=0 |
