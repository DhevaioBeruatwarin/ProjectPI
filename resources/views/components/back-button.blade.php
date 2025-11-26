@once
    <style>
        .global-back-button {
            position: sticky;
            top: 12px;
            margin: 12px 0 16px 12px;
            z-index: 50;
            width: fit-content;
        }

        .global-back-button button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 999px;
            border: none;
            background: rgba(35, 35, 35, 0.75);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .global-back-button button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .global-back-button button svg {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 575px) {
            .global-back-button {
                margin-left: 8px;
                margin-right: 8px;
            }

            .global-back-button button {
                padding: 7px 12px;
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

