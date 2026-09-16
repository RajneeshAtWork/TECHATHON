<?php

$organizer =
    $organizer ?? [];

$hackathon =
    $hackathon ?? [];


$status =
    strtolower(
        (string) (
            $hackathon['status']
            ?? 'draft'
        )
    );


$participationType =
    strtolower(
        (string) (
            $hackathon['participation_type']
            ?? 'individual'
        )
    );


$registrationCount =
    (int) (
        $hackathon['registration_count']
        ?? 0
    );


$participantCount =
    (int) (
        $hackathon['participant_count']
        ?? 0
    );


$teamCount =
    (int) (
        $hackathon['team_count']
        ?? 0
    );


$projectCount =
    (int) (
        $hackathon['project_count']
        ?? 0
    );


$submissionCount =
    (int) (
        $hackathon['submission_count']
        ?? 0
    );


$judgeCount =
    (int) (
        $hackathon['judge_count']
        ?? 0
    );


$maxTeams =
    $hackathon['max_teams']
    ?? null;


$maxParticipants =
    $hackathon['max_participants']
    ?? null;


/*
|--------------------------------------------------------------------------
| Status badge
|--------------------------------------------------------------------------
*/

$statusBadge =
    match ($status) {
        'draft' =>
            'bg-secondary',

        'pending_approval' =>
            'bg-warning text-dark',

        'approved' =>
            'bg-success',

        'registration_open' =>
            'bg-primary',

        'registration_closed' =>
            'bg-secondary',

        'ongoing' =>
            'bg-warning text-dark',

        'submission_closed' =>
            'bg-secondary',

        'judging' =>
            'bg-info text-dark',

        'results_published' =>
            'bg-success',

        'completed' =>
            'bg-dark',

        default =>
            'bg-secondary',
    };


$displayStatus =
    ucwords(
        str_replace(
            '_',
            ' ',
            $status
        )
    );


$participationLabel =
    match ($participationType) {
        'team' =>
            'Team',

        'both' =>
            'Individual + Team',

        default =>
            'Individual',
    };

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        <?= htmlspecialchars(
            $title ?? 'Manage Hackathon'
        ) ?>

        - TECHATHON

    </title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


    <!-- ================================================================
     NAVBAR
     ================================================================ -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

        <div class="container">


            <a href="/TECHATHON/public/organizer" class="navbar-brand fw-bold">
                TECHATHON
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#organizerNavbar"
                aria-controls="organizerNavbar" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="organizerNavbar">


                <ul class="navbar-nav me-auto mb-2 mb-lg-0">


                    <li class="nav-item">

                        <a class="nav-link" href="/TECHATHON/public/organizer">
                            Dashboard
                        </a>

                    </li>


                </ul>


                <div class="d-flex
                flex-column
                flex-lg-row
                align-items-lg-center
                gap-2">


                    <span class="text-white-50 small">

                        <?= htmlspecialchars(
                            $organizer['organization_name']
                            ??
                            $organizer['name']
                            ??
                            'Organizer'
                        ) ?>

                    </span>


                    <form method="POST" action="/TECHATHON/public/logout" class="m-0">

                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Logout
                        </button>

                    </form>


                </div>


            </div>

        </div>

    </nav>


    <!-- ================================================================
     MAIN
     ================================================================ -->

    <div class="container py-4">


        <!-- BACK -->

        <div class="mb-3">

            <a href="/TECHATHON/public/organizer" class="btn btn-outline-secondary btn-sm">
                &larr; Back to Organizer Dashboard
            </a>

        </div>


        <!-- HEADER -->

        <div class="d-flex
        justify-content-between
        align-items-start
        flex-wrap
        gap-3
        mb-4">


            <div>

                <h1 class="fw-bold mb-1">

                    <?= htmlspecialchars(
                        $hackathon['title']
                        ?? ''
                    ) ?>

                </h1>


                <div class="text-muted">

                    <?= htmlspecialchars(
                        $hackathon[
                            'category_name'
                        ]
                        ?? 'Uncategorized'
                    ) ?>

                    &middot;

                    <?= htmlspecialchars(
                        $participationLabel
                    ) ?>

                </div>

            </div>


            <span class="badge
            <?= $statusBadge ?>
            fs-6">

                <?= htmlspecialchars(
                    $displayStatus
                ) ?>

            </span>


        </div>


        <!-- ============================================================
         STATISTICS
         ============================================================ -->

        <div class="row g-4 mb-4">


            <!-- REGISTRATIONS -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Registrations
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $registrationCount ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- PARTICIPANTS -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Participants
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $participantCount ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- TEAMS -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Teams
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $teamCount ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- PROJECTS -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Projects
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $projectCount ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- SUBMISSIONS -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Submissions
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $submissionCount ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- JUDGES -->

            <div class="col-md-4 col-xl-2">

                <div class="card
                border-0
                shadow-sm
                h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-1">
                            Judges
                        </div>

                        <h2 class="fw-bold mb-0">

                            <?= $judgeCount ?>

                        </h2>

                    </div>

                </div>

            </div>


        </div>


        <!-- ============================================================
         CAPACITY
         ============================================================ -->

        <div class="row g-4 mb-4">


            <?php if (
                $maxParticipants !== null
            ): ?>


                <div class="col-lg-6">

                    <div class="card
                    border-0
                    shadow-sm
                    h-100">

                        <div class="card-body p-4">


                            <h5 class="fw-bold mb-3">
                                Participant Capacity
                            </h5>


                            <div class="d-flex
                            justify-content-between
                            mb-2">

                                <span>
                                    Registered participants
                                </span>

                                <strong>

                                    <?= $participantCount ?>

                                    /

                                    <?= (int) $maxParticipants ?>

                                </strong>

                            </div>


                            <?php

                            $participantPercent =
                                (int) $maxParticipants > 0
                                ? min(
                                    100,
                                    (
                                        $participantCount /
                                        (int) $maxParticipants
                                    ) * 100
                                )
                                : 0;

                            ?>


                            <div class="progress" role="progressbar" aria-valuenow="<?= (int) $participantPercent ?>"
                                aria-valuemin="0" aria-valuemax="100">

                                <div class="progress-bar" style="width: <?= (float) $participantPercent ?>%;"></div>

                            </div>


                        </div>

                    </div>

                </div>


            <?php endif; ?>


            <?php if (
                $maxTeams !== null
            ): ?>


                <div class="col-lg-6">

                    <div class="card
                    border-0
                    shadow-sm
                    h-100">

                        <div class="card-body p-4">


                            <h5 class="fw-bold mb-3">
                                Team Capacity
                            </h5>


                            <div class="d-flex
                            justify-content-between
                            mb-2">

                                <span>
                                    Registered teams
                                </span>

                                <strong>

                                    <?= $teamCount ?>

                                    /

                                    <?= (int) $maxTeams ?>

                                </strong>

                            </div>


                            <?php

                            $teamPercent =
                                (int) $maxTeams > 0
                                ? min(
                                    100,
                                    (
                                        $teamCount /
                                        (int) $maxTeams
                                    ) * 100
                                )
                                : 0;

                            ?>


                            <div class="progress" role="progressbar" aria-valuenow="<?= (int) $teamPercent ?>"
                                aria-valuemin="0" aria-valuemax="100">

                                <div class="progress-bar" style="width: <?= (float) $teamPercent ?>%;"></div>

                            </div>


                        </div>

                    </div>

                </div>


            <?php endif; ?>


        </div>


        <!-- ============================================================
         MANAGEMENT MODULES
         ============================================================ -->

        <div class="card
        border-0
        shadow-sm
        mb-4">

            <div class="card-body p-4">


                <h3 class="fw-bold mb-1">
                    Hackathon Management
                </h3>


                <p class="text-muted mb-4">

                    Manage participants, teams, projects,
                    submissions, judges, evaluation criteria,
                    and results.

                </p>


                <div class="row g-3">


                    <!-- REGISTRATIONS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Registrations
                            </h5>

                            <p class="text-muted
                            small">

                                View participants and
                                registration types.

                            </p>

                            <a href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/registrations"
                                class="btn btn-outline-primary btn-sm">
                                View Registrations
                            </a>

                        </div>

                    </div>


                    <!-- TEAMS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Teams
                            </h5>

                            <p class="text-muted
                            small">

                                View participating teams
                                and members.

                            </p>

                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                Coming Next
                            </button>

                        </div>

                    </div>


                    <!-- PROJECTS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Projects
                            </h5>

                            <p class="text-muted
                            small">

                                View projects submitted
                                by participants.

                            </p>

                            <a href="#" class="btn btn-outline-primary btn-sm disabled" aria-disabled="true">
                                Coming Next
                            </a>

                        </div>

                    </div>


                    <!-- SUBMISSIONS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Submissions
                            </h5>

                            <p class="text-muted
                            small">

                                Review submitted projects
                                and versions.

                            </p>

                            <a href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/submissions"
                                class="btn btn-outline-primary btn-sm">
                                View Submissions
                            </a>

                        </div>

                    </div>


                    <!-- JUDGES -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Judges
                            </h5>

                            <p class="text-muted
                            small">

                                Assign judges to this
                                hackathon.

                            </p>

                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                Coming Next
                            </button>

                        </div>

                    </div>


                    <!-- EVALUATION CRITERIA -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Evaluation Criteria
                            </h5>

                            <p class="text-muted
                            small">

                                Configure scoring criteria
                                for judges.

                            </p>

                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                Coming Next
                            </button>

                        </div>

                    </div>


                    <!-- RESULTS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Results
                            </h5>

                            <p class="text-muted
                            small">

                                Review scores and publish
                                final results.

                            </p>

                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                Coming Next
                            </button>

                        </div>

                    </div>


                    <!-- ANNOUNCEMENTS -->

                    <div class="col-md-6 col-xl-4">

                        <div class="border
                        rounded
                        p-3
                        h-100">

                            <h5 class="fw-bold">
                                Announcements
                            </h5>

                            <p class="text-muted
                            small">

                                Send updates to participants
                                and teams.

                            </p>

                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                Coming Next
                            </button>

                        </div>

                    </div>


                </div>


            </div>

        </div>


        <!-- ============================================================
         HACKATHON INFORMATION
         ============================================================ -->

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">


                <h3 class="fw-bold mb-4">
                    Hackathon Information
                </h3>


                <div class="row g-4">


                    <!-- PARTICIPATION -->

                    <div class="col-md-6">


                        <div class="text-muted small">
                            Participation
                        </div>

                        <strong>
                            <?= htmlspecialchars(
                                $participationLabel
                            ) ?>
                        </strong>


                    </div>


                    <!-- TEAM SIZE -->

                    <div class="col-md-6">


                        <div class="text-muted small">
                            Team Size
                        </div>

                        <strong>

                            <?php if (
                                in_array(
                                    $participationType,
                                    [
                                        'team',
                                        'both'
                                    ],
                                    true
                                )
                            ): ?>

                                <?= (int) (
                                    $hackathon[
                                        'min_team_size'
                                    ]
                                    ?? 0
                                ) ?>

                                -

                                <?= (int) (
                                    $hackathon[
                                        'max_team_size'
                                    ]
                                    ?? 0
                                ) ?>

                                members

                            <?php else: ?>

                                Not applicable

                            <?php endif; ?>

                        </strong>


                    </div>


                    <!-- REGISTRATION -->

                    <div class="col-md-6">


                        <div class="text-muted small">
                            Registration
                        </div>

                        <strong>

                            <?php if (
                                !empty(
                                $hackathon[
                                    'registration_start'
                                ]
                            )
                            ): ?>

                                <?= htmlspecialchars(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $hackathon[
                                                'registration_start'
                                            ]
                                        )
                                    )
                                ) ?>

                                &ndash;

                                <?= htmlspecialchars(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $hackathon[
                                                'registration_end'
                                            ]
                                        )
                                    )
                                ) ?>

                            <?php else: ?>

                                Not configured

                            <?php endif; ?>

                        </strong>


                    </div>


                    <!-- HACKATHON DATES -->

                    <div class="col-md-6">


                        <div class="text-muted small">
                            Hackathon Dates
                        </div>

                        <strong>

                            <?php if (
                                !empty(
                                $hackathon[
                                    'hackathon_start'
                                ]
                            )
                            ): ?>

                                <?= htmlspecialchars(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $hackathon[
                                                'hackathon_start'
                                            ]
                                        )
                                    )
                                ) ?>

                            <?php endif; ?>


                            <?php if (
                                !empty(
                                $hackathon[
                                    'hackathon_end'
                                ]
                            )
                            ): ?>

                                <br>

                                <span class="text-muted">
                                    to
                                </span>

                                <?= htmlspecialchars(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $hackathon[
                                                'hackathon_end'
                                            ]
                                        )
                                    )
                                ) ?>

                            <?php endif; ?>

                        </strong>


                    </div>


                    <!-- SUBMISSION DEADLINE -->

                    <div class="col-12">


                        <div class="text-muted small">
                            Submission Deadline
                        </div>

                        <strong>

                            <?php if (
                                !empty(
                                $hackathon[
                                    'submission_deadline'
                                ]
                            )
                            ): ?>

                                <?= htmlspecialchars(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $hackathon[
                                                'submission_deadline'
                                            ]
                                        )
                                    )
                                ) ?>

                            <?php else: ?>

                                Not configured

                            <?php endif; ?>

                        </strong>


                    </div>


                </div>


            </div>

        </div>


    </div>


    <!-- ================================================================
     BOOTSTRAP JS
     ================================================================ -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>