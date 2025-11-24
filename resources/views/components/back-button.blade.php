@once
    <style>
        .global-back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
        }

        .global-back-button button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            border: none;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .global-back-button button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.3);
        }

        .global-back-button button svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 575px) {
            .global-back-button {
                top: 12px;
                left: 12px;
            }

            .global-back-button button {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
@endonce

<div class="global-back-button">
    <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.assign('{{ url('/') }}');">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </button>
</div>

