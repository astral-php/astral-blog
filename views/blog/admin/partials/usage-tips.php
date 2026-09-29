<?php
/**
 * Conseil d’utilisation admin (auth / inscriptions).
 */
?>
<details class="mb-6 group rounded-xl border border-sand-deep bg-accent-soft/80 open:shadow-sm" style="background:var(--accent-soft);border-color:var(--line)">
    <summary class="cursor-pointer list-none px-4 py-3 text-sm font-semibold flex items-center justify-between gap-2" style="color:var(--ink)">
        <span>Conseils — inscriptions utilisateurs</span>
        <span class="text-xs font-normal group-open:hidden" style="color:var(--muted)">afficher</span>
        <span class="text-xs font-normal hidden group-open:inline" style="color:var(--muted)">masquer</span>
    </summary>
    <div class="px-4 pb-4 text-sm space-y-2 border-t pt-3" style="color:var(--muted);border-color:var(--line)">
        <p>
            Le <strong style="color:var(--ink)">premier compte</strong> créé devient administrateur.
            Réglez ensuite le mode d’inscription dans <code class="bg-white/80 px-1 rounded text-xs">.env</code> :
        </p>
        <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg px-3 py-2 overflow-x-auto">AUTH_REGISTRATION=direct   # ou confirm</pre>
        <ul class="list-disc list-inside space-y-1">
            <li><code class="bg-white/80 px-1 rounded text-xs">direct</code> — accès immédiat</li>
            <li><code class="bg-white/80 px-1 rounded text-xs">confirm</code> — confirmation e-mail (mailer)</li>
        </ul>
    </div>
</details>
