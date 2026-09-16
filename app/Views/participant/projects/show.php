<?php

use App\Core\Session;

$project =
    $project ?? [];

$registration =
    $registration ?? null;

$latestSubmission =
    $latestSubmission ?? null;

$submissions =
    $submissions ?? [];

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
        <?= htmlspecialchars(
            $project['title'] ?? 'Project'
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
            href="/TECHATHON/public/participant/projects"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; My Projects
        </a>

    </div>


    <div class="row g-4">


        <!-- PROJECT -->

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <div
                        class="d-flex
                        justify-content-between
                        align-items-start
                        gap-3 mb-4"
                    >

                        <div>

                            <h1 class="fw-bold mb-1">

                                <?= htmlspecialchars(
                                    $project['title'] ?? ''
                                ) ?>

                            </h1>

                            <div class="text-muted">

                                <?= htmlspecialchars(
                                    $project[
                                        'hackathon_title'
                                    ] ?? ''
                                ) ?>

                            </div>

                        </div>


                        <?php if (
                            $latestSubmission
                            && (
                                $latestSubmission['status']
                                ?? ''
                            ) === 'submitted'
                        ): ?>

                            <span class="badge bg-success fs-6">
                                Submitted
                            </span>

                        <?php else: ?>

                            <span class="badge bg-secondary fs-6">
                                Draft
                            </span>

                        <?php endif; ?>

                    </div>


                    <?php if (
                        !empty(
                            $project['team_name']
                        )
                    ): ?>

                        <div class="alert alert-info">

                            <strong>
                                Team:
                            </strong>

                            <?= htmlspecialchars(
                                $project['team_name']
                            ) ?>

                        </div>

                    <?php endif; ?>


                    <h4 class="mb-3">
                        Description
                    </h4>

                    <div class="text-muted mb-4">

                        <?= nl2br(
                            htmlspecialchars(
                                $project[
                                    'description'
                                ] ?? ''
                            )
                        ) ?>

                    </div>


                    <!-- LINKS -->

                    <?php if (
                        !empty(
                            $project['github_url']
                        )
                        ||
                        !empty(
                            $project['demo_url']
                        )
                        ||
                        !empty(
                            $project['video_url']
                        )
                    ): ?>

                        <h4 class="mb-3">
                            Project Links
                        </h4>


                        <div
                            class="d-flex
                            flex-wrap
                            gap-2
                            mb-4"
                        >

                            <?php if (
                                !empty(
                                    $project['github_url']
                                )
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['github_url']
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
                                    $project['demo_url']
                                )
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['demo_url']
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
                                    $project['video_url']
                                )
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['video_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-danger"
                                >
                                    Presentation / Video
                                </a>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>


                    <!-- SUBMISSION -->

                    <hr class="my-4">


                    <h4 class="mb-3">
                        Submission
                    </h4>


                    <?php if (
                        $latestSubmission
                    ): ?>

                        <div class="border rounded p-3 mb-3">

                            <div
                                class="d-flex
                                justify-content-between
                                align-items-center
                                mb-2"
                            >

                                <strong>

                                    Version
                                    <?= (int) (
                                        $latestSubmission[
                                            'version'
                                        ] ?? 1
                                    ) ?>

                                </strong>


                                <span
                                    class="badge
                                    <?=
                                        (
                                            (
                                                $latestSubmission[
                                                    'status'
                                                ] ?? ''
                                            ) === 'submitted'
                                        )
                                            ? 'bg-success'
                                            : (
                                                (
                                                    $latestSubmission[
                                                        'status'
                                                    ] ?? ''
                                                ) === 'late'
                                                    ? 'bg-danger'
                                                    : 'bg-secondary'
                                            )
                                    ?>"
                                >

                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $latestSubmission[
                                                'status'
                                            ] ?? ''
                                        )
                                    ) ?>

                                </span>

                            </div>


                            <?php if (
                                !empty(
                                    $latestSubmission[
                                        'submission_notes'
                                    ]
                                )
                            ): ?>

                                <div class="text-muted">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $latestSubmission[
                                                'submission_notes'
                                            ]
                                        )
                                    ) ?>

                                </div>

                            <?php endif; ?>


                            <?php if (
                                !empty(
                                    $latestSubmission[
                                        'submitted_at'
                                    ]
                                )
                            ): ?>

                                <div
                                    class="small
                                    text-muted
                                    mt-2"
                                >

                                    Submitted on

                                    <?= htmlspecialchars(
                                        date(
                                            'd M Y, h:i A',
                                            strtotime(
                                                $latestSubmission[
                                                    'submitted_at'
                                                ]
                                            )
                                        )
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>


                    <?php else: ?>

                        <div class="alert alert-warning">

                            No submission has been created yet.

                        </div>

                    <?php endif; ?>


                    <!-- SUBMIT FORM -->

                    <?php if (
                        !$latestSubmission
                        ||
                        (
                            $latestSubmission[
                                'status'
                            ] ?? ''
                        ) === 'draft'
                    ): ?>

                        <form
                            method="POST"
                            action="/TECHATHON/public/participant/projects/<?= (int) $project['id'] ?>/submit"
                        >

                            <input
                                type="hidden"
                                name="_csrf_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken
                                ) ?>"
                            >


                            <div class="mb-3">

                                <label
                                    for="submission_notes"
                                    class="form-label fw-semibold"
                                >
                                    Submission Notes
                                </label>

                                <textarea
                                    class="form-control"
                                    id="submission_notes"
                                    name="submission_notes"
                                    rows="5"
                                    placeholder="Add a short explanation for the judges about this submission."
                                ></textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Submit Project
                            </button>

                        </form>


                    <?php else: ?>

                        <div class="alert alert-success">

                            This project has been submitted.

                        </div>

                    <?php endif; ?>


                </div>

            </div>

        </div>


        <!-- REGISTRATION -->

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Hackathon Registration
                    </h5>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Participation
                        </div>

                        <strong class="text-capitalize">

                            <?= htmlspecialchars(
                                $registration[
                                    'registration_type'
                                ]
                                ?? ''
                            ) ?>

                        </strong>

                    </div>


                    <?php if (
                        !empty(
                            $registration['team_name']
                        )
                    ): ?>

                        <div>

                            <div class="text-muted small">
                                Team
                            </div>

                            <strong>

                                <?= htmlspecialchars(
                                    $registration[
                                        'team_name'
                                    ]
                                ) ?>

                            </strong>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- VERSION HISTORY -->

            <?php if (
                !empty($submissions)
            ): ?>

                <div
                    class="card
                    border-0
                    shadow-sm
                    mt-4"
                >

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Submission History
                        </h5>


                        <?php foreach (
                            $submissions
                            as $submission
                        ): ?>

                            <div
                                class="border-bottom
                                pb-3
                                mb-3"
                            >

                                <div class="fw-semibold">

                                    Version
                                    <?= (int) (
                                        $submission[
                                            'version'
                                        ]
                                    ) ?>

                                </div>

                                <div
                                    class="small
                                    text-muted
                                    text-capitalize"
                                >

                                    <?= htmlspecialchars(
                                        $submission[
                                            'status'
                                        ] ?? ''
                                    ) ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>


        </div>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>