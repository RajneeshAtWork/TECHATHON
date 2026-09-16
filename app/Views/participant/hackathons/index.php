<?php

$hackathons = $hackathons ?? [];

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
            $title ?? 'Browse Hackathons'
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
         PAGE HEADER
         ============================================================ -->

    <div class="mb-4">

        <h1 class="mb-1">
            Browse Hackathons
        </h1>

        <p class="text-muted mb-0">
            Discover hackathons and find the right challenge for you.
        </p>

    </div>


    <!-- ============================================================
         HACKATHON LIST
         ============================================================ -->

    <?php if (empty($hackathons)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <h4 class="mb-2">
                    No hackathons available
                </h4>

                <p class="text-muted mb-0">
                    There are currently no approved hackathons
                    available for participation.
                </p>

            </div>

        </div>

    <?php else: ?>


        <div class="row g-4">


            <?php foreach ($hackathons as $hackathon): ?>


                <?php

                /*
                 * ----------------------------------------------------
                 * STATUS
                 * ----------------------------------------------------
                 */

                $status =
                    strtolower(
                        (string) (
                            $hackathon['status']
                            ?? 'approved'
                        )
                    );

                $statusClass = 'secondary';

                if ($status === 'registration_open') {

                    $statusClass = 'success';

                } elseif ($status === 'approved') {

                    $statusClass = 'primary';

                }

                $statusLabel =
                    ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $status
                        )
                    );


                /*
                 * ----------------------------------------------------
                 * PARTICIPATION TYPE
                 * ----------------------------------------------------
                 */

                $participationType =
                    $hackathon['participation_type']
                    ?? 'individual';

                $participationLabel =
                    ucwords(
                        $participationType
                    );


                /*
                 * ----------------------------------------------------
                 * REGISTRATION START
                 *
                 * We intentionally display ONLY the start date.
                 * registration_end remains in the database and can
                 * still be used by the registration logic.
                 * ----------------------------------------------------
                 */

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


                /*
                 * ----------------------------------------------------
                 * HACKATHON START
                 * ----------------------------------------------------
                 */

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


                /*
                 * ----------------------------------------------------
                 * HACKATHON END
                 * ----------------------------------------------------
                 */

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


                <div class="col-md-6 col-xl-4">


                    <div class="card border-0 shadow-sm h-100">


                        <div class="card-body d-flex flex-column">


                            <!-- ====================================================
                                 TITLE + STATUS
                                 ==================================================== -->

                            <div
                                class="d-flex justify-content-between align-items-start gap-2 mb-3"
                            >

                                <div>

                                    <h4 class="card-title mb-1">

                                        <?= htmlspecialchars(
                                            $hackathon['title'] ?? ''
                                        ) ?>

                                    </h4>


                                    <?php if (
                                        !empty(
                                            $hackathon[
                                                'category_name'
                                            ]
                                        )
                                    ): ?>

                                        <small class="text-muted">

                                            <?= htmlspecialchars(
                                                $hackathon[
                                                    'category_name'
                                                ]
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </div>


                                <span
                                    class="badge bg-<?= htmlspecialchars(
                                        $statusClass
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        $statusLabel
                                    ) ?>

                                </span>

                            </div>


                            <!-- ====================================================
                                 DESCRIPTION
                                 ==================================================== -->

                            <p class="text-muted mb-3">

                                <?= htmlspecialchars(
                                    mb_strimwidth(
                                        (string) (
                                            $hackathon[
                                                'description'
                                            ]
                                            ?? ''
                                        ),
                                        0,
                                        180,
                                        '...'
                                    )
                                ) ?>

                            </p>


                            <!-- ====================================================
                                 ORGANIZER
                                 ==================================================== -->

                            <div class="mb-2">

                                <strong>
                                    Organizer:
                                </strong>

                                <?= htmlspecialchars(
                                    $hackathon[
                                        'organizer_name'
                                    ]
                                    ?? '—'
                                ) ?>

                            </div>


                            <!-- ====================================================
                                 PARTICIPATION
                                 ==================================================== -->

                            <div class="mb-2">

                                <strong>
                                    Participation:
                                </strong>

                                <span class="badge bg-light text-dark">

                                    <?= htmlspecialchars(
                                        $participationLabel
                                    ) ?>

                                </span>

                            </div>


                            <!-- ====================================================
                                 TEAM SIZE
                                 ==================================================== -->

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

                                <div class="mb-2">

                                    <strong>
                                        Team Size:
                                    </strong>

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

                                </div>

                            <?php endif; ?>


                            <!-- ====================================================
                                 REGISTRATION
                                 ==================================================== -->

                            <div class="mb-3">

                                <strong>
                                    Registration:
                                </strong>


                                <div class="small text-muted mt-1">


                                    <?php if (
                                        $registrationStart !== null
                                    ): ?>

                                        From
                                        <?= htmlspecialchars(
                                            $registrationStart->format(
                                                'd M Y, h:i A'
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        Start date unavailable

                                    <?php endif; ?>


                                </div>

                            </div>


                            <!-- ====================================================
                                 HACKATHON DATES
                                 ==================================================== -->

                            <div class="mb-3">

                                <strong>
                                    Hackathon:
                                </strong>


                                <div class="small text-muted mt-1">


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


                            <!-- ====================================================
                                 ACTION
                                 ==================================================== -->

                            <div class="mt-auto pt-2">

                                <a
                                    href="/TECHATHON/public/participant/hackathons/<?= (int) (
                                        $hackathon['id']
                                    ) ?>"
                                    class="btn btn-primary w-100"
                                >
                                    View Details
                                </a>

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