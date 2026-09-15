<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title) ?> - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="/TECHATHON/public/organizer"
        >
            TECHATHON
        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">
                <?= htmlspecialchars(
                    $user['name'] ?? 'Organizer'
                ) ?>
            </span>

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


<div class="container py-5">

    <div class="mb-4">

        <a
            href="/TECHATHON/public/organizer"
            class="text-decoration-none"
        >
            ← Back to Organizer Dashboard
        </a>

        <h1 class="fw-bold mt-3">
            Edit Hackathon
        </h1>

        <p class="text-muted">
            You can edit this hackathon during the first
            24 hours after creation.
        </p>

    </div>


    <?php if (
        $error = \App\Core\Session::getFlash('error')
    ): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <?php if (
        $success = \App\Core\Session::getFlash('success')
    ): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>


    <div class="alert alert-warning">

        <strong>24-hour editing window:</strong>

        This hackathon can be edited until

        <?php
        $createdAt = new DateTimeImmutable(
            $hackathon['created_at']
        );

        $editDeadline =
            $createdAt->modify('+24 hours');
        ?>

        <?= htmlspecialchars(
            $editDeadline->format(
                'd M Y, h:i A'
            )
        ) ?>

    </div>


    <form
        method="POST"
        action="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/edit"
    >

        <input
            type="hidden"
            name="_csrf_token"
            value="<?= htmlspecialchars($csrfToken) ?>"
        >


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Basic Information
                </h4>

                <div class="mb-3">

                    <label
                        for="title"
                        class="form-label fw-semibold"
                    >
                        Hackathon Title
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="title"
                        name="title"
                        maxlength="200"
                        required
                        value="<?= htmlspecialchars(
                            $hackathon['title']
                        ) ?>"
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="category_id"
                        class="form-label fw-semibold"
                    >
                        Category
                    </label>

                    <select
                        class="form-select"
                        id="category_id"
                        name="category_id"
                        required
                    >

                        <option value="">
                            Select category
                        </option>

                        <?php foreach (
                            $categories as $category
                        ): ?>

                            <option
                                value="<?= (int) $category['id'] ?>"
                                <?= (
                                    (int) $category['id'] ===
                                    (int) $hackathon['category_id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>
                            >
                                <?= htmlspecialchars(
                                    $category['name']
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="mb-3">

                    <label
                        for="description"
                        class="form-label fw-semibold"
                    >
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="5"
                    ><?= htmlspecialchars(
                        $hackathon['description'] ?? ''
                    ) ?></textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="rules"
                        class="form-label fw-semibold"
                    >
                        Rules
                    </label>

                    <textarea
                        class="form-control"
                        id="rules"
                        name="rules"
                        rows="5"
                    ><?= htmlspecialchars(
                        $hackathon['rules'] ?? ''
                    ) ?></textarea>

                </div>


                <div>

                    <label
                        for="requirements"
                        class="form-label fw-semibold"
                    >
                        Requirements
                    </label>

                    <textarea
                        class="form-control"
                        id="requirements"
                        name="requirements"
                        rows="5"
                    ><?= htmlspecialchars(
                        $hackathon['requirements'] ?? ''
                    ) ?></textarea>

                </div>

            </div>

        </div>


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Participation Settings
                </h4>


                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Participation Type
                    </label>


                    <?php
                    $participation =
                        $hackathon['participation_type'];
                    ?>


                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="participation_type"
                            id="individual"
                            value="individual"
                            <?= $participation === 'individual'
                                ? 'checked'
                                : ''
                            ?>
                        >

                        <label
                            class="form-check-label"
                            for="individual"
                        >
                            <strong>Individual</strong>
                            <br>
                            <small class="text-muted">
                                Participants register individually.
                            </small>
                        </label>

                    </div>


                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="participation_type"
                            id="team"
                            value="team"
                            <?= $participation === 'team'
                                ? 'checked'
                                : ''
                            ?>
                        >

                        <label
                            class="form-check-label"
                            for="team"
                        >
                            <strong>Team</strong>
                            <br>
                            <small class="text-muted">
                                Participants must participate through teams.
                            </small>
                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="participation_type"
                            id="both"
                            value="both"
                            <?= $participation === 'both'
                                ? 'checked'
                                : ''
                            ?>
                        >

                        <label
                            class="form-check-label"
                            for="both"
                        >
                            <strong>Both</strong>
                            <br>
                            <small class="text-muted">
                                Participants may choose individual or team participation.
                            </small>
                        </label>

                    </div>

                </div>


                <div
                    id="teamSettings"
                    class="border rounded p-3 bg-light"
                >

                    <h6 class="fw-bold">
                        Team Settings
                    </h6>


                    <div class="row g-3">

                        <div class="col-md-4">

                            <label
                                for="min_team_size"
                                class="form-label"
                            >
                                Minimum Team Size
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="min_team_size"
                                name="min_team_size"
                                min="1"
                                value="<?= htmlspecialchars(
                                    $hackathon['min_team_size'] ?? ''
                                ) ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="max_team_size"
                                class="form-label"
                            >
                                Maximum Team Size
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="max_team_size"
                                name="max_team_size"
                                min="1"
                                value="<?= htmlspecialchars(
                                    $hackathon['max_team_size'] ?? ''
                                ) ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="max_teams"
                                class="form-label"
                            >
                                Maximum Teams
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="max_teams"
                                name="max_teams"
                                min="1"
                                value="<?= htmlspecialchars(
                                    $hackathon['max_teams'] ?? ''
                                ) ?>"
                            >

                            <div class="form-text">
                                Counts teams, not members.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="mt-3">

                    <label
                        for="max_participants"
                        class="form-label fw-semibold"
                    >
                        Maximum Participants
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="max_participants"
                        name="max_participants"
                        min="1"
                        value="<?= htmlspecialchars(
                            $hackathon['max_participants'] ?? ''
                        ) ?>"
                        required
                    >

                </div>

            </div>

        </div>


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Schedule
                </h4>


                <div class="row g-3">

                    <?php
                    function editDateValue(
                        ?string $value
                    ): string {
                        if (!$value) {
                            return '';
                        }

                        return date(
                            'Y-m-d\TH:i',
                            strtotime($value)
                        );
                    }
                    ?>


                    <div class="col-md-6">

                        <label
                            for="registration_start"
                            class="form-label fw-semibold"
                        >
                            Registration Start
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="registration_start"
                            name="registration_start"
                            required
                            value="<?= editDateValue(
                                $hackathon['registration_start']
                            ) ?>"
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="registration_end"
                            class="form-label fw-semibold"
                        >
                            Registration End
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="registration_end"
                            name="registration_end"
                            required
                            value="<?= editDateValue(
                                $hackathon['registration_end']
                            ) ?>"
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="hackathon_start"
                            class="form-label fw-semibold"
                        >
                            Hackathon Start
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="hackathon_start"
                            name="hackathon_start"
                            required
                            value="<?= editDateValue(
                                $hackathon['hackathon_start']
                            ) ?>"
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="hackathon_end"
                            class="form-label fw-semibold"
                        >
                            Hackathon End
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="hackathon_end"
                            name="hackathon_end"
                            required
                            value="<?= editDateValue(
                                $hackathon['hackathon_end']
                            ) ?>"
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="submission_deadline"
                            class="form-label fw-semibold"
                        >
                            Submission Deadline
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="submission_deadline"
                            name="submission_deadline"
                            required
                            value="<?= editDateValue(
                                $hackathon['submission_deadline']
                            ) ?>"
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a
                href="/TECHATHON/public/organizer"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary px-4"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>


<script>

const participationInputs =
    document.querySelectorAll(
        'input[name="participation_type"]'
    );

const teamSettings =
    document.getElementById(
        'teamSettings'
    );

const minTeamSize =
    document.getElementById(
        'min_team_size'
    );

const maxTeamSize =
    document.getElementById(
        'max_team_size'
    );

const maxTeams =
    document.getElementById(
        'max_teams'
    );


function updateTeamSettings() {

    const selected =
        document.querySelector(
            'input[name="participation_type"]:checked'
        ).value;

    const teamRequired =
        selected === 'team' ||
        selected === 'both';

    teamSettings.style.opacity =
        teamRequired ? '1' : '0.5';

    minTeamSize.required =
        teamRequired;

    maxTeamSize.required =
        teamRequired;

    maxTeams.required =
        teamRequired;

    if (!teamRequired) {

        minTeamSize.value = '';
        maxTeamSize.value = '';
        maxTeams.value = '';

    }

}


participationInputs.forEach(
    input => {

        input.addEventListener(
            'change',
            updateTeamSettings
        );

    }
);


updateTeamSettings();

</script>

</body>
</html>