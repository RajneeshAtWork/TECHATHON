<?php

use App\Core\Session;

$submission =
    $submission ?? [];

$criteria =
    $criteria ?? [];

$existingEvaluations =
    $existingEvaluations ?? [];

$csrfToken =
    $csrfToken ?? '';

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
        Evaluate Submission - TECHATHON
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


    <?php if (
        $success = Session::getFlash('success')
    ): ?>

        <div class="alert alert-success">

            <?= htmlspecialchars($success) ?>

        </div>

    <?php endif; ?>


    <?php if (
        $error = Session::getFlash('error')
    ): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <div class="mb-3">

        <a
            href="/TECHATHON/public/judge/hackathons/<?= (int) $submission['hackathon_id'] ?>/submissions"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to Submissions
        </a>

    </div>


    <div class="row g-4">


        <!-- PROJECT -->

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <h1 class="fw-bold mb-1">

                        <?= htmlspecialchars(
                            $submission[
                                'project_title'
                            ] ?? ''
                        ) ?>

                    </h1>


                    <p class="text-muted">

                        <?= htmlspecialchars(
                            $submission[
                                'hackathon_title'
                            ] ?? ''
                        ) ?>

                    </p>


                    <?php if (
                        !empty(
                            $submission['team_name']
                        )
                    ): ?>

                        <div class="alert alert-info">

                            <strong>
                                Team:
                            </strong>

                            <?= htmlspecialchars(
                                $submission[
                                    'team_name'
                                ]
                            ) ?>

                        </div>

                    <?php endif; ?>


                    <h4>
                        Project Description
                    </h4>

                    <div class="text-muted mb-4">

                        <?= nl2br(
                            htmlspecialchars(
                                $submission[
                                    'project_description'
                                ] ?? ''
                            )
                        ) ?>

                    </div>


                    <?php if (
                        !empty(
                            $submission[
                                'submission_notes'
                            ]
                        )
                    ): ?>

                        <h4>
                            Submission Notes
                        </h4>

                        <div class="text-muted mb-4">

                            <?= nl2br(
                                htmlspecialchars(
                                    $submission[
                                        'submission_notes'
                                    ]
                                )
                            ) ?>

                        </div>

                    <?php endif; ?>


                    <h4>
                        Project Links
                    </h4>


                    <div
                        class="d-flex
                        flex-wrap
                        gap-2"
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
                                class="btn btn-outline-dark"
                            >
                                GitHub Repository
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
                                class="btn btn-outline-primary"
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
                                class="btn btn-outline-danger"
                            >
                                Presentation / Video
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- EVALUATION -->

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <h3 class="fw-bold mb-1">
                        Evaluation
                    </h3>

                    <p class="text-muted mb-4">

                        Score each criterion according to
                        the configured maximum score.

                    </p>


                    <?php if (
                        empty($criteria)
                    ): ?>

                        <div class="alert alert-warning">

                            No evaluation criteria have been
                            configured for this hackathon.

                        </div>


                    <?php else: ?>


                        <?php

                        $alreadyEvaluated =
                            !empty(
                                $existingEvaluations
                            );

                        ?>


                        <?php if (
                            $alreadyEvaluated
                        ): ?>

                            <div class="alert alert-info">

                                You have already submitted
                                an evaluation for this submission.

                            </div>


                            <?php foreach (
                                $existingEvaluations
                                as $evaluation
                            ): ?>

                                <div
                                    class="border
                                    rounded
                                    p-3
                                    mb-3"
                                >

                                    <div
                                        class="d-flex
                                        justify-content-between"
                                    >

                                        <strong>

                                            <?= htmlspecialchars(
                                                $evaluation[
                                                    'criteria_name'
                                                ]
                                            ) ?>

                                        </strong>

                                        <span>

                                            <?= htmlspecialchars(
                                                $evaluation[
                                                    'score'
                                                ]
                                            ) ?>

                                            /

                                            <?= htmlspecialchars(
                                                $evaluation[
                                                    'max_score'
                                                ]
                                            ) ?>

                                        </span>

                                    </div>

                                    <?php if (
                                        !empty(
                                            $evaluation[
                                                'feedback'
                                            ]
                                        )
                                    ): ?>

                                        <div
                                            class="small
                                            text-muted
                                            mt-2"
                                        >

                                            <?= nl2br(
                                                htmlspecialchars(
                                                    $evaluation[
                                                        'feedback'
                                                    ]
                                                )
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>


                        <?php else: ?>


                            <form
                                method="POST"
                                action="/TECHATHON/public/judge/submissions/<?= (int) $submission['submission_id'] ?>/evaluate"
                            >


                                <input
                                    type="hidden"
                                    name="_csrf_token"
                                    value="<?= htmlspecialchars(
                                        $csrfToken
                                    ) ?>"
                                >


                                <?php foreach (
                                    $criteria
                                    as $criterion
                                ): ?>

                                    <div class="mb-4">


                                        <label
                                            for="score_<?= (int) $criterion['id'] ?>"
                                            class="form-label fw-semibold"
                                        >

                                            <?= htmlspecialchars(
                                                $criterion['name']
                                            ) ?>

                                        </label>


                                        <?php if (
                                            !empty(
                                                $criterion[
                                                    'description'
                                                ]
                                            )
                                        ): ?>

                                            <div
                                                class="form-text mb-2"
                                            >

                                                <?= htmlspecialchars(
                                                    $criterion[
                                                        'description'
                                                    ]
                                                ) ?>

                                            </div>

                                        <?php endif; ?>


                                        <div
                                            class="input-group"
                                        >

                                            <input
                                                type="number"
                                                class="form-control"
                                                id="score_<?= (int) $criterion['id'] ?>"
                                                name="scores[<?= (int) $criterion['id'] ?>]"
                                                min="0"
                                                max="<?= htmlspecialchars(
                                                    $criterion[
                                                        'max_score'
                                                    ]
                                                ) ?>"
                                                step="0.01"
                                                required
                                            >

                                            <span
                                                class="input-group-text"
                                            >

                                                /
                                                <?= htmlspecialchars(
                                                    $criterion[
                                                        'max_score'
                                                    ]
                                                ) ?>

                                            </span>

                                        </div>


                                        <div
                                            class="small
                                            text-muted
                                            mt-1"
                                        >

                                            Weight:
                                            <?= htmlspecialchars(
                                                $criterion[
                                                    'weight'
                                                ]
                                            ) ?>

                                        </div>

                                    </div>

                                <?php endforeach; ?>


                                <div class="mb-4">

                                    <label
                                        for="feedback"
                                        class="form-label fw-semibold"
                                    >
                                        Feedback
                                    </label>

                                    <textarea
                                        name="feedback"
                                        id="feedback"
                                        class="form-control"
                                        rows="6"
                                        placeholder="Provide constructive feedback for the submission."
                                    ></textarea>

                                </div>


                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    Save Evaluation
                                </button>


                            </form>


                        <?php endif; ?>


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