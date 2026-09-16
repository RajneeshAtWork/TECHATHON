<?php

use App\Core\Session;

$hackathon = $hackathon ?? [];
$registration = $registration ?? null;
$teams = $teams ?? [];
$registrationWindowOpen =
    $registrationWindowOpen ?? false;
$csrfToken = $csrfToken ?? '';

$status =
    strtolower(
        (string) (
            $hackathon['status']
            ?? 'approved'
        )
    );

$participationType =
    strtolower(
        (string) (
            $hackathon['participation_type']
            ?? 'individual'
        )
    );

$participationLabel =
    $participationType === 'both'
        ? 'Individual + Team'
        : ucwords(
            $participationType
        );

$registrationStart = null;

if (
    !empty(
        $hackathon['registration_start']
    )
) {
    try {

        $registrationStart =
            new DateTimeImmutable(
                $hackathon[
                    'registration_start'
                ]
            );

    } catch (Exception $exception) {

        $registrationStart = null;

    }
}

$hackathonStart = null;

if (
    !empty(
        $hackathon['hackathon_start']
    )
) {
    try {

        $hackathonStart =
            new DateTimeImmutable(
                $hackathon[
                    'hackathon_start'
                ]
            );

    } catch (Exception $exception) {

        $hackathonStart = null;

    }
}

$hackathonEnd = null;

if (
    !empty(
        $hackathon['hackathon_end']
    )
) {
    try {

        $hackathonEnd =
            new DateTimeImmutable(
                $hackathon[
                    'hackathon_end'
                ]
            );

    } catch (Exception $exception) {

        $hackathonEnd = null;

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars(
            $title ?? 'Hackathon Details'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">
<?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

<div class="container py-4">


    <!-- ============================================================
         BACK
         ============================================================ -->

    <div class="mb-3">

        <a
            href="/TECHATHON/public/participant/hackathons"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to Hackathons
        </a>

    </div>


    <!-- ============================================================
         FLASH MESSAGES
         ============================================================ -->

    <?php if ($success = Session::getFlash('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= htmlspecialchars($success) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <?php if ($error = Session::getFlash('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?= htmlspecialchars($error) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- ============================================================
         MAIN DETAILS
         ============================================================ -->

    <div class="row g-4">


        <!-- ========================================================
             LEFT COLUMN
             ======================================================== -->

        <div class="col-lg-8">


            <div class="card border-0 shadow-sm">


                <div class="card-body p-4">


                    <!-- TITLE -->

                    <div
                        class="d-flex flex-wrap
                        justify-content-between
                        align-items-start
                        gap-3 mb-4"
                    >

                        <div>

                            <h1 class="mb-1">

                                <?= htmlspecialchars(
                                    $hackathon['title'] ?? ''
                                ) ?>

                            </h1>


                            <?php if (
                                !empty(
                                    $hackathon[
                                        'category_name'
                                    ]
                                )
                            ): ?>

                                <div class="text-muted">

                                    <?= htmlspecialchars(
                                        $hackathon[
                                            'category_name'
                                        ]
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>


                        <span class="badge bg-success fs-6">

                            <?= htmlspecialchars(
                                ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $status
                                    )
                                )
                            ) ?>

                        </span>

                    </div>


                    <!-- ORGANIZER -->

                    <div class="mb-4">

                        <strong>
                            Organizer:
                        </strong>

                        <?= htmlspecialchars(
                            $hackathon[
                                'organizer_name'
                            ] ?? '—'
                        ) ?>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="mb-4">

                        <h5>
                            Description
                        </h5>

                        <div class="text-muted">

                            <?= nl2br(
                                htmlspecialchars(
                                    $hackathon[
                                        'description'
                                    ] ?? 'No description provided.'
                                )
                            ) ?>

                        </div>

                    </div>


                    <!-- RULES -->

                    <?php if (
                        !empty(
                            $hackathon['rules']
                        )
                    ): ?>

                        <div class="mb-4">

                            <h5>
                                Rules
                            </h5>

                            <div class="text-muted">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $hackathon['rules']
                                    )
                                ) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- REQUIREMENTS -->

                    <?php if (
                        !empty(
                            $hackathon[
                                'requirements'
                            ]
                        )
                    ): ?>

                        <div class="mb-4">

                            <h5>
                                Requirements
                            </h5>

                            <div class="text-muted">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $hackathon[
                                            'requirements'
                                        ]
                                    )
                                ) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- PARTICIPATION -->

                    <div class="mb-4">

                        <h5>
                            Participation
                        </h5>

                        <span class="badge bg-primary">

                            <?= htmlspecialchars(
                                $participationLabel
                            ) ?>

                        </span>

                    </div>


                    <!-- TEAM SIZE -->

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

                        <div class="mb-4">

                            <h5>
                                Team Size
                            </h5>

                            <?= (int) (
                                $hackathon[
                                    'min_team_size'
                                ] ?? 0
                            ) ?>

                            -

                            <?= (int) (
                                $hackathon[
                                    'max_team_size'
                                ] ?? 0
                            ) ?>

                            members

                        </div>

                    <?php endif; ?>


                    <!-- REGISTRATION -->

                    <div class="mb-4">

                        <h5>
                            Registration
                        </h5>

                        <?php if (
                            $registrationStart !== null
                        ): ?>

                            <div class="text-muted">

                                From
                                <?= htmlspecialchars(
                                    $registrationStart->format(
                                        'd M Y, h:i A'
                                    )
                                ) ?>

                            </div>

                        <?php else: ?>

                            <div class="text-muted">

                                Start date unavailable

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- HACKATHON DATES -->

                    <div class="mb-0">

                        <h5>
                            Hackathon Dates
                        </h5>

                        <div class="text-muted">

                            <?php if (
                                $hackathonStart !== null
                            ): ?>

                                <?= htmlspecialchars(
                                    $hackathonStart->format(
                                        'd M Y, h:i A'
                                    )
                                ) ?>

                            <?php else: ?>

                                Start date unavailable

                            <?php endif; ?>


                            <br>


                            <?php if (
                                $hackathonEnd !== null
                            ): ?>

                                to

                                <?= htmlspecialchars(
                                    $hackathonEnd->format(
                                        'd M Y, h:i A'
                                    )
                                ) ?>

                            <?php else: ?>

                                End date unavailable

                            <?php endif; ?>

                        </div>

                    </div>


                </div>

            </div>


        </div>


        <!-- ========================================================
             RIGHT COLUMN
             ======================================================== -->

        <div class="col-lg-4">


            <div class="card border-0 shadow-sm">


                <div class="card-body p-4">


                    <h4 class="mb-3">
                        Registration
                    </h4>


                    <?php if ($registration): ?>


                        <!-- ALREADY REGISTERED -->

                        <div class="alert alert-success">

                            <strong>
                                You are registered.
                            </strong>

                            <div class="small mt-2">

                                Type:

                                <?= htmlspecialchars(
                                    ucwords(
                                        $registration[
                                            'registration_type'
                                        ]
                                    )
                                ) ?>

                            </div>


                            <?php if (
                                !empty(
                                    $registration[
                                        'team_name'
                                    ]
                                )
                            ): ?>

                                <div class="small mt-1">

                                    Team:

                                    <?= htmlspecialchars(
                                        $registration[
                                            'team_name'
                                        ]
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>


                    <?php elseif (
                        !$registrationWindowOpen
                    ): ?>


                        <!-- REGISTRATION NOT OPEN -->

                        <div class="alert alert-warning">

                            Registration is not currently open.

                        </div>


                        <?php if (
                            $registrationStart !== null
                        ): ?>

                            <p class="text-muted small mb-0">

                                Registration opens on

                                <strong>

                                    <?= htmlspecialchars(
                                        $registrationStart->format(
                                            'd M Y, h:i A'
                                        )
                                    ) ?>

                                </strong>

                            </p>

                        <?php endif; ?>


                    <?php else: ?>


                        <!-- REGISTRATION FORM -->

                        <form
                            method="POST"
                            action="/TECHATHON/public/participant/hackathons/<?= (int) $hackathon['id'] ?>/register"
                        >


                            <input
                                type="hidden"
                                name="_csrf_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken
                                ) ?>"
                            >


                            <?php if (
                                in_array(
                                    $participationType,
                                    [
                                        'individual',
                                        'both'
                                    ],
                                    true
                                )
                            ): ?>

                                <div class="form-check mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="registration_type"
                                        id="individual"
                                        value="individual"
                                        required
                                    >

                                    <label
                                        class="form-check-label"
                                        for="individual"
                                    >

                                        <strong>
                                            Individual
                                        </strong>

                                        <div class="small text-muted">

                                            Participate on your own.

                                        </div>

                                    </label>

                                </div>

                            <?php endif; ?>


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


                                <div class="form-check mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="registration_type"
                                        id="team"
                                        value="team"
                                        <?= $participationType === 'team'
                                            ? 'required'
                                            : '' ?>
                                    >

                                    <label
                                        class="form-check-label"
                                        for="team"
                                    >

                                        <strong>
                                            Team
                                        </strong>

                                        <div class="small text-muted">

                                            Participate with a team.

                                        </div>

                                    </label>

                                </div>


                                <div class="mb-3">

                                    <label
                                        for="team_id"
                                        class="form-label"
                                    >
                                        Select Team
                                    </label>


                                    <?php if (
                                        empty($teams)
                                    ): ?>

                                        <div class="alert alert-info small">

                                            You are not a member of any team yet.

                                            Team registration will be available
                                            after you join or create a team.

                                        </div>


                                        <select
                                            class="form-select"
                                            name="team_id"
                                            id="team_id"
                                            disabled
                                        >

                                            <option>
                                                No teams available
                                            </option>

                                        </select>

                                    <?php else: ?>

                                        <select
                                            class="form-select"
                                            name="team_id"
                                            id="team_id"
                                        >

                                            <option value="">
                                                Select a team
                                            </option>

                                            <?php foreach (
                                                $teams
                                                as $team
                                            ): ?>

                                                <option
                                                    value="<?= (int) $team['id'] ?>"
                                                >

                                                    <?= htmlspecialchars(
                                                        $team['name']
                                                    ) ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    <?php endif; ?>

                                </div>


                            <?php endif; ?>


                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                <?= (
                                    $participationType === 'team'
                                    && empty($teams)
                                )
                                    ? 'disabled'
                                    : '' ?>
                            >
                                Register
                            </button>


                        </form>


                    <?php endif; ?>


                </div>

            </div>


            <!-- ====================================================
                 CAPACITY
                 ==================================================== -->

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Capacity
                    </h5>


                    <?php if (
                        in_array(
                            $participationType,
                            [
                                'individual',
                                'both'
                            ],
                            true
                        )
                        && $hackathon[
                            'max_participants'
                        ] !== null
                    ): ?>

                        <div class="small text-muted mb-2">

                            Individual participants:

                            <strong>

                                <?= (int) (
                                    $hackathon[
                                        'max_participants'
                                    ]
                                ) ?>

                            </strong>

                        </div>

                    <?php endif; ?>


                    <?php if (
                        in_array(
                            $participationType,
                            [
                                'team',
                                'both'
                            ],
                            true
                        )
                        && $hackathon[
                            'max_teams'
                        ] !== null
                    ): ?>

                        <div class="small text-muted">

                            Teams:

                            <strong>

                                <?= (int) (
                                    $hackathon[
                                        'max_teams'
                                    ]
                                ) ?>

                            </strong>

                        </div>

                    <?php endif; ?>


                    <?php if (
                        $hackathon[
                            'max_participants'
                        ] === null
                        && $hackathon[
                            'max_teams'
                        ] === null
                    ): ?>

                        <div class="text-muted small">

                            No registration capacity limit.

                        </div>

                    <?php endif; ?>


                </div>

            </div>


        </div>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>