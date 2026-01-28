let currentDate = new Date();
        let pickMode = 'start';
        let startDate = null;
        let endDate = null;

        function renderCalendar() {
            const monthYear = document.getElementById('calMonthYear');
            const daysContainer = document.getElementById('calDays');
            daysContainer.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            monthYear.innerText = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            let startSpace = firstDay === 0 ? 6 : firstDay - 1;

           
            for(let i=0; i<startSpace; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.classList.add('cal-day', 'empty');
                daysContainer.appendChild(emptyDiv);
            }

           
            for(let i=1; i<=daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('cal-day');
                dayDiv.innerText = i;
                
                
                let dateStr = `${year}-${String(month+1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                
                if(startDate === dateStr) dayDiv.classList.add('selected', 'start-range');
                if(endDate === dateStr) dayDiv.classList.add('selected', 'end-range');
                if(startDate && endDate && dateStr > startDate && dateStr < endDate) dayDiv.classList.add('in-range');

                dayDiv.onclick = () => selectDate(dateStr);
                daysContainer.appendChild(dayDiv);
            }
        }

        function selectDate(dateStr) {
            if(pickMode === 'start') {
                startDate = dateStr;
                document.getElementById('displayStart').innerText = dateStr;
                document.getElementById('realStart').value = dateStr + "T09:00";
                pickMode = 'end';
                document.getElementById('displayStart').classList.remove('active');
                document.getElementById('displayEnd').classList.add('active');
            } else {
                if(dateStr < startDate) {
                    startDate = dateStr;
                    document.getElementById('displayStart').innerText = dateStr;
                    document.getElementById('realStart').value = dateStr + "T09:00";
                } else {
                    endDate = dateStr;
                    document.getElementById('displayEnd').innerText = dateStr;
                    document.getElementById('realEnd').value = dateStr + "T18:00";
                    pickMode = 'start';
                    document.getElementById('displayEnd').classList.remove('active');
                }
            }
            renderCalendar();
        }

        function changeMonth(dir) {
            currentDate.setMonth(currentDate.getMonth() + dir);
            renderCalendar();
        }

        function goToToday() {
            currentDate = new Date();
            renderCalendar();
        }

        function setPickMode(mode) {
            pickMode = mode;
            document.getElementById('displayStart').classList.toggle('active', mode === 'start');
            document.getElementById('displayEnd').classList.toggle('active', mode === 'end');
        }

     
        document.getElementById('displayStart').classList.add('active');
        renderCalendar();

        function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        const icon = document.querySelector('.notify-icon-container');
        
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
            icon.classList.remove('active');
        } else {
            dropdown.style.display = 'block';
            icon.classList.add('active');
        }
    }
    
    document.addEventListener('click', function(event) {
        const wrapper = document.querySelector('.notify-wrapper');
        const dropdown = document.getElementById('notificationDropdown');
        
        if (wrapper && !wrapper.contains(event.target)) {
            dropdown.style.display = 'none';
            document.querySelector('.notify-icon-container').classList.remove('active');
        }
    });
