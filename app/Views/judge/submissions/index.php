<?php

$submissions =
    $submissions ?? [];

$hackathonId =
    (int) ($hackathonId ?? 0);

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
        Hackathon Submissions - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<nav class="navbar navbar-dark bg-dark shadow-sm">

    <div class="container">

        <a
            href="/TECHATHON/public/judge"
            class="navbar-brand fw-bold"
        >
            TECHATHON
        </a>


        <div class="d-flex align-items-center gap-3">

            <a
                href="/TECHATHON/public/judge"
                class="btn btn-outline-light btn-sm"
            >
                Dashboard
            </a>


            <form
                method="POST"
                action="/TECHATHON/public/logout"
                class="m-0"
            >

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm"
                >
                    Logout
                </button>

            </form>

        </div>

    </div>

</nav>


<div class="container py-4">


    <div class="mb-4">

        <a
            href="/TECHATHON/public/judge"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Judge Dashboard
        </a>

    </div>


    <div class="mb-4">

        <h1 class="fw-bold mb-1">
            Hackathon Submissions
        </h1>

        <p class="text-muted mb-0">
            Review submissions assigned to you.
        </p>

    </div>


    <?php if (empty($submissions)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-5 text-center">

                <h4>
                    No submissions available
                </h4>

                <p class="text-muted mb-0">
                    There are currently no submitted projects
                    for this assignment.
                </p>

            </div>

        </div>


    <?php else: ?>


        <div class="row g-4">


            <?php foreach (
                $submissions
                as $submission
            ): ?>

                <div class="col-lg-6">


                    <div
                        class="card
                        border-0
                        shadow-sm
                        h-100"
                    >


                        <div class="card-body p-4">


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
                                            $submission[
                                                'project_title'
                                            ] ?? ''
                                        ) ?>

                                    </h4>


                                    <div
                                        class="text-muted small"
                                    >

                                        Version
                                        <?= (int) (
                                            $submission[
                                                'version'
                                            ] ?? 1
                                        ) ?>

                                    </div>

                                </div>


                                <span
                                    class="badge
                                    <?= (
                                        (
                                            $submission[
                                                'status'
                                            ] ?? ''
                                        ) === 'late'
                                    )
                                        ? 'bg-danger'
                                        : 'bg-success'
                                    ?>"
                                >

                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $submission[
                                                'status'
                                            ] ?? ''
                                        )
                                    ) ?>

                                </span>

                            </div>


                            <?php if (
                                !empty(
                                    $submission['team_name']
                                )
                            ): ?>

                                <div class="mb-3">

                                    <div
                                        class="text-muted small"
                                    >
                                        Team
                                    </div>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $submission[
                                                'team_name'
                                            ]
                                        ) ?>

                                    </strong>

                                </div>

                            <?php endif; ?>


                            <p class="text-muted">

                                <?= htmlspecialchars(
                                    mb_strimwidth(
                                        $submission[
                                            'project_description'
                                        ] ?? '',
                                        0,
                                        220,
                                        '...'
                                    )
                                ) ?>

                            </p>


                            <div
                                class="d-flex
                                flex-wrap
                                gap-2
                                mb-3"
                            >

                                <?php if (
                                    !empty(
                                        $submission[
                                            'github_url'
                                        ]
                                    )
                                ): ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $submission[
                                                'github_url'
                                            ]
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-sm btn-outline-dark"
                                    >
                                        GitHub
                                    </a>

                                <?php endif; ?>


                                <?php if (
                                    !empty(
                                        $submission[
                                            'demo_url'
                                        ]
                                    )
                                ): ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $submission[
                                                'demo_url'
                                            ]
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Live Demo
                                    </a>

                                <?php endif; ?>


                                <?php if (
                                    !empty(
                                        $submission[
                                            'video_url'
                                        ]
                                    )
                                ): ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $submission[
                                                'video_url'
                                            ]
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        Video
                                    </a>

                                <?php endif; ?>

                            </div>


                            <a
                                href="/TECHATHON/public/judge/submissions/<?= (int) $submission['submission_id'] ?>/evaluate"
                                class="btn btn-primary"
                            >
                                Evaluate Submission
                            </a>


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