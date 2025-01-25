<?php
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';

class ReservationComponent {
    private ReservationRepository $reservationRepository;
    private ClientRepository $clientRepository;
    private ScreeningRepository $screeningRepository;

    public function __construct()
    {
        $this->reservationRepository = new ReservationRepository();
        $this->clientRepository = new ClientRepository();
        $this->screeningRepository = new ScreeningRepository();
    }

    public function addReservation(string $email): string {
        $successes = [];
        $fails = [];
        foreach ($_POST['ID_Seat'] as $index => &$seat) {
            try {
                $reservation = new Reservation(
                    ID_Seat: intval($seat),
                    ID_Screening: intval($_POST['ID_Screening']),
                    ID_Discount: $index === 0 ? $this->reservationRepository->getDiscountID($_POST['discount_name']) : null,
                    ID_Client: $this->clientRepository->getClient($email)->ID_Client,
                    vat_percentage: 23
                );

                $reservation = $this->reservationRepository->addReservation($reservation);
                $successes[] = intval($seat);
            } catch (Exception $e) {
                $fails[] = intval($seat);
            }
        }
        $resp['successes'] = $successes;
        $resp['fails'] = $fails;
        $resp['new_data'] = $this->screeningRepository->getScreeningsByMovieIdAssoc($_POST['ID_Movie'] ?? -1);
        return json_encode($resp);

}
}