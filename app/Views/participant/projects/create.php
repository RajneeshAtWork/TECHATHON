<?php

$registration =
    $registration ?? [];

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
        Create Project - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

<?php require __DIR__ . '/../partials/navbar.php'; ?>


<div class="container py-4">


    <div class="mb-4">

        <a
            href="/TECHATHON/public/participant/registrations"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to Registrations
        </a>

    </div>


    <div class="row justify-content-center">

        <div class="col-lg-8">


            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <h1 class="fw-bold mb-1">
                        Create Project
                    </h1>

                    <p class="text-muted mb-4">

                        <?= htmlspecialchars(
                            $registration[
                                'hackathon_title'
                            ] ?? 'Hackathon'
                        ) ?>

                    </p>


                    <?php if (
                        !empty(
                            $registration['team_name']
                        )
                    ): ?>

                        <div class="alert alert-info">

                            <strong>
                                Team:
                            </strong>

                            <?= htmlspecialchars(
                                $registration[
                                    'team_name'
                                ]
                            ) ?>

                        </div>

                    <?php endif; ?>


                    <form
                        method="POST"
                        action="/TECHATHON/public/participant/registrations/<?= (int) $registration['id'] ?>/project/create"
                    >


                        <input
                            type="hidden"
                            name="_csrf_token"
                            value="<?= htmlspecialchars(
                                $csrfToken
                            ) ?>"
                        >


                        <!-- TITLE -->

                        <div class="mb-4">

                            <label
                                for="title"
                                class="form-label fw-semibold"
                            >
                                Project Title
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                maxlength="255"
                                required
                            >

                        </div>


                        <!-- DESCRIPTION -->

                        <div class="mb-4">

                            <label
                                for="description"
                                class="form-label fw-semibold"
                            >
                                Project Description
                            </label>

                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="7"
                                required
                                placeholder="Explain your project, the problem it solves, how it works, and the technologies you used."
                            ></textarea>

                        </div>


                        <!-- GITHUB -->

                        <div class="mb-4">

                            <label
                                for="github_url"
                                class="form-label fw-semibold"
                            >
                                GitHub Repository
                            </label>

                            <input
                                type="url"
                                class="form-control"
                                id="github_url"
                                name="github_url"
                                placeholder="https://github.com/username/project"
                            >

                            <div class="form-text">
                                Recommended for judges and organizers.
                            </div>

                        </div>


                        <!-- DEMO -->

                        <div class="mb-4">

                            <label
                                for="demo_url"
                                class="form-label fw-semibold"
                            >
                                Live Demo URL
                            </label>

                            <input
                                type="url"
                                class="form-control"
                                id="demo_url"
                                name="demo_url"
                                placeholder="https://example.com"
                            >

                        </div>


                        <!-- VIDEO -->

                        <div class="mb-4">

                            <label
                                for="video_url"
                                class="form-label fw-semibold"
                            >
                                Presentation / Video URL
                            </label>

                            <input
                                type="url"
                                class="form-control"
                                id="video_url"
                                name="video_url"
                                placeholder="https://youtube.com/..."
                            >

                        </div>


                        <div
                            class="d-flex
                            justify-content-between
                            gap-2"
                        >

                            <a
                                href="/TECHATHON/public/participant/registrations"
                                class="btn btn-outline-secondary"
                            >
                                Cancel
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Create Project
                            </button>

                        </div>


                    </form>


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