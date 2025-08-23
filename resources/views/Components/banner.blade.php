<style>
    .line {
        width: 47%; /* Relative width */
        height: 15px; /* Fixed height, but you can make it relative if needed */
        background: linear-gradient(90deg, var(--bg-gradient-end), var(--bg-gradient-start));
        border: black 1px solid;
    }

    .banner-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: center;
        align-items: center;
        margin-bottom: 2.5%;
    }

    .banner-text {
        overflow: hidden;
        text-align: center;
        border: var(--card-border) 4px solid;
        border-radius: 10px;
        background-color: var(--card-bg);
        width: auto;
        min-width: 15%;
        padding: 10px; /* Add padding for better spacing */
        color: var(--text-color);
        text-shadow: 0 0 var(--text-shadow) var(--text-color);
    }

    .banner-text div {
        font-size: 2.4rem; /* Default font size */
    }

    /* Media Queries for Responsive Design */
    @media (max-width: 1200px) {
        .banner-text div {
            font-size: 2.2rem; /* Smaller font size for medium screens */
        }
    }

    @media (max-width: 768px) {
        .banner-text div {
            font-size: 2rem; /* Smaller font size for tablets */
        }

        .line {
            width: 40%; /* Adjust line width for smaller screens */
        }
    }

    @media (max-width: 480px) {
        .banner-text div {
            font-size: 1.5rem; /* Smaller font size for mobile devices */
        }

        .line {
            width: 30%; /* Adjust line width for very small screens */
        }

        .banner-text {
            min-width: 30%; /* Adjust banner width for mobile */
        }
    }
</style>

<div class="banner-container">
    <div class="line" style="margin-left: auto; margin-right: auto; border-right: none;"></div>
    <div class="banner-text">
        <div>
            {{ $slot }}
        </div>
    </div>
    <div class="line"
        style="margin-left: auto; margin-right: auto; background: linear-gradient(90deg, var(--bg-gradient-start), var(--bg-gradient-end)); border-left: none;">
    </div>
</div>