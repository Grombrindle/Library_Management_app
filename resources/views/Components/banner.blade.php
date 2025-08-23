<style>
    .banner-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
        padding: 0 1rem;
        margin-top:2rem;
        position: relative;
    }

    .line {
        width: 50%;
        height: 15px;
        background: linear-gradient(90deg, var(--bg-gradient-end), var(--bg-gradient-start));
        border: var(--card-border) 1px solid;
        transition: width 0.3s ease;
    }

    .banner-text {
        overflow: hidden;
        text-align: center;
        border: var(--card-border) 4px solid;
        border-radius: 10px;
        background-color: var(--card-bg);
        width: auto;
        min-width: 15%;
        padding: 1rem;
        color: var(--text-color);
        text-shadow: 0 0 var(--text-shadow) var(--text-color);
        transition: all 0.3s ease;
        margin: 0 0;
        z-index: 2;
    }

    .banner-text div {
        font-size: 2.4rem;
        font-weight: 600;
        white-space: nowrap;
        transition: font-size 0.3s ease;
    }

    /* Media Queries for Responsive Design */
    @media (max-width: 1200px) {
        .banner-text div {
            font-size: 2.2rem;
        }

        .line {
            width: 42%;
        }
    }

    @media (max-width: 992px) {
        .banner-text div {
            font-size: 2rem;
        }

        .line {
            width: 38%;
        }

        .banner-text {
            min-width: 20%;
        }
    }

    @media (max-width: 768px) {
        .banner-container {
            padding: 0;
        }

        .line {
            width: calc(50% - 100px);
            position: absolute;
        }

        .line:first-child {
            left: 0;
        }

        .line:last-child {
            right: 0;
        }

        .banner-text {
            min-width: 200px;
            padding: 0.8rem;
            margin: 0 10px;
        }

        .banner-text div {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 576px) {
        .line {
            width: calc(50% - 80px);
        }

        .banner-text {
            min-width: 160px;
            padding: 0.6rem;
            margin: 0 8px;
        }

        .banner-text div {
            font-size: 1.6rem;
        }
    }

    @media (max-width: 480px) {
        .line {
            width: calc(50% - 60px);
        }

        .banner-text {
            min-width: 120px;
            padding: 0.5rem;
            margin: 0 5px;
        }

        .banner-text div {
            font-size: 3rem;
        }
    }

    /* RTL Support */
    [dir="rtl"] .banner-text {
        text-align: center;
    }

    [dir="rtl"] .line:first-child {
        background: linear-gradient(90deg, var(--bg-gradient-start), var(--bg-gradient-end));
    }

    [dir="rtl"] .line:last-child {
        background: linear-gradient(90deg, var(--bg-gradient-end), var(--bg-gradient-start));
    }

    .Banner {
        background: var(--card-bg);
        font-size: clamp(14px, 1.5vw + 8px, 20px);
        border: var(--card-border) clamp(2px, 0.5vw, 4px) solid;
        color: var(--text-color);
        border-radius: clamp(2px, 0.5vw, 3px);
        display: flex;
        flex-direction: row;
        transition: all 0.3s ease;
        transform: translateY(-2px);
        align-items: center;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: clamp(150px, 80vw, 800px);
        margin-left: auto;
        margin-right: auto;
        padding: clamp(2%, 3vw, 4%);
    }

    .BannerTitle {
        font-size: clamp(16px, 2vw + 10px, 24px);
        font-weight: bold;
        margin-bottom: clamp(1%, 2vw, 2%);
        text-align: center;
        width: 100%;
    }

    .BannerContent {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: clamp(1%, 2vw, 2%);
    }

    .BannerImage {
        width: clamp(15%, 18vw, 20%);
        height: auto;
        object-fit: cover;
        border-radius: clamp(2px, 0.5vw, 3px);
        margin-right: clamp(2%, 3vw, 4%);
    }

    /* Remove all media queries and replace with clamp-based scaling */
    @media (max-width: 768px) {
        .Banner {
            flex-direction: column;
            padding: clamp(1%, 2vw, 3%);
        }

        .BannerTitle {
            font-size: clamp(14px, 1.8vw + 8px, 20px);
        }

        .BannerImage {
            width: clamp(60%, 70vw, 80%);
            margin: 0 auto clamp(5px, 1vw, 10px) auto;
        }
    }

    /* Remove all other media queries as they're now handled by clamp() */
</style>

<div class="banner-container">
    <div class="line" style="margin-left: auto; margin-right: auto; border-right: none;"></div>
    <div class="banner-text">
        <div>
            {{ $slot }}
        </div>
    </div>
    <div class="line" style="margin-left: auto; margin-right: auto; background: linear-gradient(90deg, var(--bg-gradient-start), var(--bg-gradient-end)); border-left: none;"></div>
</div>
