    (function(){
        'use strict';

        /* ── Preview numbers animation ── */
        function animNum(id, target, suffix){
            const el = document.getElementById(id);
            if(!el) return;
            const dur = 1200, t0 = performance.now();
            (function frame(now){
                const p = Math.min((now-t0)/dur,1);
                const e = 1-Math.pow(1-p,3);
                el.textContent = Math.round(target*e)+(suffix||'');
                if(p<1) requestAnimationFrame(frame);
                else el.textContent = target+(suffix||'');
            })(t0);
        }

        /* ── Ring animation ── */
        function animRing(){
            const ring = document.getElementById('pvRingFg');
            if(!ring) return;
            const circ = 201.06;
            const rate = 0.67;
            setTimeout(()=>{
                ring.style.transition = 'stroke-dashoffset 1.4s cubic-bezier(.22,1,.36,1)';
                ring.style.strokeDashoffset = circ - (rate*circ);
            }, 600);
        }

        /* Run preview animations on load */
        setTimeout(()=>{
            animNum('pvNotes', 12);
            animNum('pvTodo',  18);
            animNum('pvPct',   67, '%');
            animNum('pvDone',  12);
            animNum('pvPend',   6);
            animRing();
        }, 400);

        /* ── Typing animation in preview ── */
        const phrases = [
            'Nulis catatan baru…',
            'Tambah jadwal besok…',
            'Centang tugas selesai…',
        ];
        let pi = 0, ci = 0, deleting = false;
        const tyEl = document.getElementById('pvTypingText');

        function typeLoop(){
            if(!tyEl) return;
            const phrase = phrases[pi];
            if(!deleting){
                tyEl.textContent = phrase.slice(0, ++ci);
                if(ci === phrase.length){
                    deleting = true;
                    setTimeout(typeLoop, 1400);
                    return;
                }
            } else {
                tyEl.textContent = phrase.slice(0, --ci);
                if(ci === 0){
                    deleting = false;
                    pi = (pi+1) % phrases.length;
                    setTimeout(typeLoop, 300);
                    return;
                }
            }
            setTimeout(typeLoop, deleting ? 40 : 65);
        }
        setTimeout(typeLoop, 1200);

        /* ── Intersection Observer for entrance animations ── */
        const io = new IntersectionObserver((entries)=>{
            entries.forEach(e=>{
                if(e.isIntersecting){
                    e.target.classList.add('in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.feat-card, .how-step, .testi-card').forEach(el => io.observe(el));

        /* ── Stagger how steps ── */
        document.querySelectorAll('.how-step').forEach((el,i)=>{
            el.style.transitionDelay = (i * 0.12)+'s';
        });

    })();
    