<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Full Calendar</div>
                    <modal v-if="showModal" @delete="removeEvent($event)" :info="event.info" :show="showModal"
                           :save="addEvent" @close="showModal = false"/>
                    <div class="card-body">

                        <div ref="calendar">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import {Calendar} from '@fullcalendar/core';
    import dayGrid from '@fullcalendar/daygrid';
    import interaction from '@fullcalendar/interaction';
    import Modal from './Modal';

    export default {
        components: {
            Modal
        },
        data() {
            return {
                showModal: false,
                event: {info: ''},
                calendar: {}
            }
        },
        methods: {
            removeEvent(event) {
                let vm = this
                axios.delete(`/api/events/${event.id}`).then(() => {
                    let eventById = vm.calendar.getEventById(event.id)
                    eventById.remove()
                    vm.showModal = false
                })

            },
            addEvent(title) {
                var vm = this, event = vm.event

                axios[event.info.id ? 'put' : 'post'](`/api/events${event.info.id ? '/' + event.info.id : ''}`, {
                    "title": title,
                    "date": event.info.dateStr ? event.info.dateStr : ''
                }).then((response) => {
                    if (event.info.id) {
                        let eventById = vm.calendar.getEventById(event.info.id)
                        eventById.remove()
                    }
                    vm.calendar.addEvent({
                        id: response.data.id,
                        title: title,
                        start: event.info.dateStr ? event.info.dateStr : response.data.date,
                    });

                    vm.showModal = false

                })
            },
            formatDate(date) {
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var day = date.getDate();
                var newDate = year + '-' + month + '-' + day;
                return newDate;
            },
            editEventDate(info) {
                var event_id = info.event.id;
                var date = this.formatDate(info.event.start);
                axios.put(`/api/events/${event_id}`, {"id": event_id, "date": date})
            }
        },
        mounted() {
            let vm = this, calendarEl = vm.$refs.calendar;
            vm.calendar = new Calendar(calendarEl, {
                plugins: [interaction, dayGrid],
                defaultView: 'dayGridMonth',
                editable: true,
                firstDay: 1,
                eventDurationEditable: false,
                selectLongPressDelay: 0,
                events: '/api/events',
                eventDrop: function (info) {
                    vm.editEventDate(info)
                },
                eventClick: function (info) {
                    vm.showModal = true

                    vm.event = {info: info.event}
                },
                dateClick: (info) => {
                    vm.showModal = true
                    vm.event = {info: info}
                },
            })

            vm.calendar.render();

        }
    }
</script>
