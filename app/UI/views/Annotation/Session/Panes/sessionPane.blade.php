<script type="text/javascript" src="/annotation/session/script/components"></script>
<div class="text-right">
    <div x-data="sessionTrackerComponent()" x-init="init()">
        <!-- Session Status Display -->
{{--        <div class="session-indicator" :class="isActive ? 'active' : 'inactive'">--}}
{{--            <span x-text="isActive ? 'Session Active' : 'No Active Session'"></span>--}}
{{--            <span x-show="isActive" x-text="' - Started: ' + startedAt"></span>--}}
{{--        </div>--}}

        <!-- Control Buttons -->
        <button
            class="ui secondary tiny button"
            :class="{ 'disabled': isActive }"
            :disabled="isActive"
            @click="startSession({{$idDocumentSentence}})"
            x-show="!isActive"
{{--            x-text="isActive ? 'Session Running' : 'Start Session'">--}}
    > Start session
        </button>

        <button
            class="ui danger tiny button"
            :class="{ 'disabled': !isActive }"
            :disabled="!isActive"
            @click="endSession({{$idDocumentSentence}})"
            x-show="isActive">
            End Session
        </button>

        <!-- Session Info -->
{{--        <div x-show="isActive" class="mt-4">--}}
{{--            <p><strong>Session Token:</strong> <span x-text="sessionToken"></span></p>--}}
{{--            <p><strong>Last Heartbeat:</strong> <span x-text="lastHeartbeat"></span></p>--}}
{{--            <p><strong>Duration:</strong> <span x-text="duration"></span> seconds</p>--}}
{{--        </div>--}}

{{--        <!-- Heartbeat Log (for debugging) -->--}}
{{--        <div class="heartbeat-log">--}}
{{--            <h4>Heartbeat Log:</h4>--}}
{{--            <template x-for="log in heartbeatLogs" :key="log.timestamp">--}}
{{--                <div x-text="log.message"></div>--}}
{{--            </template>--}}
{{--        </div>--}}
    </div>

</div>
