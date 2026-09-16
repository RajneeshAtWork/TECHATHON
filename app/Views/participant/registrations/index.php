<?php

use App\Core\Session;
use App\Models\Project;

$registrations =
    $registrations ?? [];

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
            $title ?? 'My Registrations'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<?php require __DIR__ . '/../partials/navbar.php'; ?>


<div class="container py-4">


    <!-- ============================================================
         HEADER
         ============================================================ -->

    <div
        class="d-flex
        justify-content-between
        align-items-center
        flex-wrap
        gap-3
        mb-4"
    >

        <div>

            <h1 class="fw-bold mb-1">
                My Registrations
            </h1>

            <p class="text-muted mb-0">

                Hackathons you are currently registered for.

            </p>

        </div>


        <a
            href="/TECHATHON/public/participant/hackathons"
            class="btn btn-outline-primary"
        >
            Browse Hackathons
        </a>

    </div>


    <!-- ============================================================
         FLASH MESSAGES
         ============================================================ -->

    <?php if (
        $success = Session::getFlash('success')
    ): ?>

        <div
            class="alert
            alert-success
            alert-dismissible
            fade
            show"
            role="alert"
        >

            <?= htmlspecialchars($success) ?>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    <?php endif; ?>


    <?php if (
        $error = Session::getFlash('error')
    ): ?>

        <div
            class="alert
            alert-danger
            alert-dismissible
            fade
            show"
            role="alert"
        >

            <?= htmlspecialchars($error) ?>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    <?php endif; ?>


    <!-- ============================================================
         REGISTRATIONS
         ============================================================ -->

    <?php if (empty($registrations)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-5 text-center">


                <div class="mb-3">

                    <span
                        class="fs-1"
                        aria-hidden="true"
                    >
                        &#128203;
                    </span>

                </div>


                <h4 class="mb-2">
                    No registrations yet
                </h4>


                <p class="text-muted mb-4">

                    You are not currently registered
                    for any hackathons.

                </p>


                <a
                    href="/TECHATHON/public/participant/hackathons"
                    class="btn btn-primary"
                >
                    Browse Hackathons
                </a>


            </div>

        </div>


    <?php else: ?>


        <div class="row g-4">


            <?php foreach (
                $registrations
                as $registration
            ): ?>


                <?php

                /*
                 * --------------------------------------------------
                 * Hackathon dates
                 * --------------------------------------------------
                 */

                $hackathonStart = null;

                if (
                    !empty(
                        $registration[
                            'hackathon_start'
                        ]
                    )
                ) {
                    try {

                        $hackathonStart =
                            new DateTime(
                                $registration[
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
                        $registration[
                            'hackathon_end'
                        ]
                    )
                ) {
                    try {

                        $hackathonEnd =
                            new DateTime(
                                $registration[
                                    'hackathon_end'
                                ]
                            );

                    } catch (Exception $exception) {

                        $hackathonEnd = null;

                    }
                }


                /*
                 * --------------------------------------------------
                 * Registration type
                 * --------------------------------------------------
                 */

                $registrationType =
                    strtolower(
                        (string) (
                            $registration[
                                'registration_type'
                            ]
                            ?? ''
                        )
                    );


                /*
                 * --------------------------------------------------
                 * Check project
                 * --------------------------------------------------
                 *
                 * This is used only to decide whether to show
                 * "Create Project" or "View Project".
                 */

                $projectModel =
                    new Project();

                $project =
                    $projectModel->findByRegistration(
                        (int) (
                            $registration['id']
                            ?? 0
                        )
                    );

                ?>


                <div class="col-lg-6">


                    <div
                        class="card
                        border-0
                        shadow-sm
                        h-100"
                    >


                        <div class="card-body p-4">


                            <!-- =================================================
                                 TITLE + STATUS
                                 ================================================= -->

                            <div
                                class="d-flex
                                justify-content-between
                                align-items-start
                                gap-3
                                mb-3"
                            >

                                <div>

                                    <h4
                                        class="fw-bold mb-1"
                                    >

                                        <?= htmlspecialchars(
                                            $registration[
                                                'hackathon_title'
                                            ] ?? ''
                                        ) ?>

                                    </h4>


                                    <?php if (
                                        !empty(
                                            $registration[
                                                'category_name'
                                            ]
                                        )
                                    ): ?>

                                        <div
                                            class="text-muted small"
                                        >

                                            <?= htmlspecialchars(
                                                $registration[
                                                    'category_name'
                                                ]
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </div>


                                <span
                                    class="badge bg-success"
                                >
                                    Registered
                                </span>


                            </div>


                            <hr>


                            <!-- =================================================
                                 PARTICIPATION
                                 ================================================= -->

                            <div class="mb-3">


                                <div
                                    class="text-muted small"
                                >
                                    Participation
                                </div>


                                <strong
                                    class="text-capitalize"
                                >

                                    <?= htmlspecialchars(
                                        $registrationType
                                    ) ?>

                                </strong>


                            </div>


                            <!-- =================================================
                                 TEAM
                                 ================================================= -->

                            <?php if (
                                $registrationType === 'team'
                            ): ?>

                                <div class="mb-3">


                                    <div
                                        class="text-muted small"
                                    >
                                        Team
                                    </div>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $registration[
                                                'team_name'
                                            ] ?? '—'
                                        ) ?>

                                    </strong>


                                </div>

                            <?php endif; ?>


                            <!-- =================================================
                                 HACKATHON DATES
                                 ================================================= -->

                            <div class="mb-3">


                                <div
                                    class="text-muted small"
                                >
                                    Hackathon Dates
                                </div>


                                <?php if (
                                    $hackathonStart !== null
                                ): ?>

                                    <?= htmlspecialchars(
                                        $hackathonStart->format(
                                            'd M Y, h:i A'
                                        )
                                    ) ?>

                                <?php else: ?>

                                    <span
                                        class="text-muted"
                                    >
                                        Start date unavailable
                                    </span>

                                <?php endif; ?>


                                <br>


                                <?php if (
                                    $hackathonEnd !== null
                                ): ?>

                                    <span
                                        class="text-muted"
                                    >
                                        to
                                    </span>

                                    <?= htmlspecialchars(
                                        $hackathonEnd->format(
                                            'd M Y, h:i A'
                                        )
                                    ) ?>

                                <?php else: ?>

                                    <span
                                        class="text-muted"
                                    >
                                        End date unavailable
                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 REGISTERED DATE
                                 ================================================= -->

                            <div class="mb-4">


                                <div
                                    class="text-muted small"
                                >
                                    Registered On
                                </div>


                                <?php if (
                                    !empty(
                                        $registration[
                                            'registered_at'
                                        ]
                                    )
                                ): ?>


                                    <?php

                                    try {

                                        $registeredAt =
                                            new DateTime(
                                                $registration[
                                                    'registered_at'
                                                ]
                                            );

                                        echo htmlspecialchars(
                                            $registeredAt->format(
                                                'd M Y, h:i A'
                                            )
                                        );

                                    } catch (
                                        Exception $exception
                                    ) {

                                        echo 'Date unavailable';

                                    }

                                    ?>


                                <?php else: ?>

                                    <span
                                        class="text-muted"
                                    >
                                        Date unavailable
                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 ACTIONS
                                 ================================================= -->

                            <div
                                class="d-flex
                                flex-wrap
                                gap-2"
                            >


                                <!-- View Hackathon -->

                                <a
                                    href="/TECHATHON/public/participant/hackathons/<?= (int) $registration['hackathon_id'] ?>"
                                    class="btn btn-outline-primary"
                                >
                                    View Hackathon
                                </a>


                                <!-- Project -->

                                <?php if ($project): ?>


                                    <a
                                        href="/TECHATHON/public/participant/projects/<?= (int) $project['id'] ?>"
                                        class="btn btn-success"
                                    >
                                        View Project
                                    </a>


                                <?php else: ?>


                                    <a
                                        href="/TECHATHON/public/participant/registrations/<?= (int) $registration['id'] ?>/project/create"
                                        class="btn btn-primary"
                                    >
                                        Create Project
                                    </a>


                                <?php endif; ?>


                            </div>


                        </div>

                    </div>


                </div>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>