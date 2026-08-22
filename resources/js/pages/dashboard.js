    (function(){
        'use strict';
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

        /* ── datetime ── */
        function tick(){
            const d = new Date();
            const el = document.getElementById('dbDateTime');
            if(!el) return;
            el.textContent = d.toLocaleDateString('id-ID',{
                weekday:'short', day:'numeric', month:'long',
                hour:'2-digit', minute:'2-digit'
            });
        }
        tick(); setInterval(tick, 30000);

        /* ── number animation ── */
        function animNum(id, target){
            const el = document.getElementById(id);
            if(!el) return;
            const dur = 800, t0 = performance.now(), from = parseInt(el.textContent)||0;
            (function frame(now){
                const p = Math.min((now-t0)/dur,1);
                const e = 1-Math.pow(1-p,3);
                el.textContent = Math.round(from+(target-from)*e);
                if(p<1) requestAnimationFrame(frame);
                else el.textContent = target;
            })(t0);
        }

        /* ── escape html ── */
        function esc(s){
            if(!s) return '';
            const d=document.createElement('div');
            d.textContent=s;
            return d.innerHTML;
        }

        /* ── format time ── */
        function fmtTime(v){
            if(!v) return '';
            const d = new Date(v);
            if(isNaN(d)) return v;
            return d.toLocaleDateString('id-ID',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'});
        }

        /* ── activity icon ── */
        const actClasses = { note:'c-coral', schedule:'c-sage', todo:'c-amber' };
        const actIcons   = { note:'ti-notebook', schedule:'ti-calendar-event', todo:'ti-checkbox' };
        function actIcon(type){
            const t = (type||'').toLowerCase();
            return `<i class="ti ${actIcons[t]||'ti-clock'}" aria-hidden="true"></i>`;
        }
        function actColor(type){
            return actClasses[(type||'').toLowerCase()] || '';
        }

        /* ── empty ── */
        function emptyHtml(title, sub){
            return `<div class="db-empty" role="status">
                <i class="ti ti-mood-empty" aria-hidden="true"></i>
                <p class="db-empty-t">${title}</p>
                <p class="db-empty-s">${sub}</p>
            </div>`;
        }

        /* ── render ── */
        function render(data){
            const s = data.stats || {};
            const totalNotes     = s.total_notes     || 0;
            const totalSchedules = s.total_schedules || 0;
            const totalTodos     = s.total_todos     || 0;
            const completedTodos = s.completed_todos || 0;
            const pendingTodos   = s.pending_todos   || 0;
            const rate = totalTodos > 0 ? Math.round((completedTodos/totalTodos)*100) : 0;

            /* stats */
            animNum('totalNotes',     totalNotes);
            animNum('totalSchedules', totalSchedules);
            animNum('totalTodos',     totalTodos);
            animNum('completedTodos', completedTodos);
            animNum('pendingTodos',   pendingTodos);

            /* stat bars */
            const mx = Math.max(totalNotes, totalSchedules, totalTodos, 1);
            setTimeout(()=>{
                document.getElementById('notesBar').style.width     = (totalNotes/mx*100)+'%';
                document.getElementById('schedulesBar').style.width = (totalSchedules/mx*100)+'%';
                document.getElementById('todosBar').style.width     = (totalTodos/mx*100)+'%';
            }, 100);

            /* ring */
            const ring = document.getElementById('progressRing');
            const circ = 314.16;
            if(ring) setTimeout(()=>{
                ring.style.strokeDashoffset = circ - (rate/100)*circ;
                ring.closest('[role=meter]').setAttribute('aria-valuenow', rate);
            }, 200);
            document.getElementById('progressPercent').textContent = rate+'%';

            /* today schedule */
            const today = data.today_schedules || [];
            const todayBadge = document.getElementById('todayBadge');
            if(todayBadge) todayBadge.textContent = today.length;

            const schCont = document.getElementById('todaySchedule');
            if(!today.length){
                schCont.innerHTML = emptyHtml('Tidak ada jadwal','Hari ini tampak lapang.');
            } else {
                schCont.innerHTML = today.map(s=>`
                    <div class="db-sch-item" style="border-left-color:${esc(s.color||'#c96442')}">
                        <span class="db-sch-time">${esc(s.start_time||'—')}</span>
                        <span class="db-sch-title">${esc(s.title||'')}</span>
                        <span class="db-sch-badge ${esc(s.priority||'medium')}">${esc(s.priority||'medium')}</span>
                    </div>
                `).join('');
            }

            /* recent activity */
            const acts = data.recent_activities || [];
            const actCont = document.getElementById('recentActivity');
            if(!acts.length){
                actCont.innerHTML = emptyHtml('Belum ada aktivitas','Mulai buat catatan atau jadwal.');
            } else {
                actCont.innerHTML = acts.map(a=>`
                    <div class="db-act-item">
                        <div class="db-act-avatar ${actColor(a.type)}">${actIcon(a.type)}</div>
                        <div class="db-act-body">
                            <div class="db-act-title">${esc(a.title||'')}</div>
                            <div class="db-act-meta">
                                <span>${esc(a.type||'')}</span>
                                <span class="db-act-meta-dot"></span>
                                <span>${esc(a.category||'Umum')}</span>
                            </div>
                        </div>
                        <span class="db-act-time">${fmtTime(a.time)}</span>
                    </div>
                `).join('');
            }
        }

        /* ── fetch ── */
        async function load(){
            try {
                const res = await fetch('/planner-api/dashboard',{
                    headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}
                });
                if(!res.ok) throw new Error();
                render(await res.json());
            } catch(e){
                console.error('Dashboard fetch error',e);
                setTimeout(load, 5000);
            }
        }

        load();
        setInterval(load, 60000);
    })();
    