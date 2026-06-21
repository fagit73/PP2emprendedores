<footer>
    <p>&copy; 2024 - Biblioteca IFTS 11</p>
</footer>
</body>

<?php if (!empty($datos['js'])) : ?>
    <?php foreach ($datos['js'] as $archivo) : ?>
        <script src="<?= URLAPP; ?>/public/js/<?= $archivo ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</html>